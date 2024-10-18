<?php
// approve_request.php

require_once('../../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/group/lib.php');
require_once($CFG->dirroot . '/message/lib.php'); // Include messaging library

require_login();
$context = context_system::instance();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied: Administrator access required']);
  exit;
}

header('Content-Type: application/json');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405);
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = isset($data['requestid']) ? (int)$data['requestid'] : null;

  if (!$requestId) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;

  // Retrieve the request from the table and ensure it is pending
  $request = $DB->get_record('proposed_groups_requests', ['id' => $requestId], '*', MUST_EXIST);
  if ($request->status != 0) {
    throw new Exception('Request is not pending or has already been processed', 400);
  }

  // Approve the request
  $request->status = 1;
  if (!$DB->update_record('proposed_groups_requests', $request)) {
    throw new Exception('Failed to update request status in the database');
  }

  // Create the site-level group
  $newgroup = new stdClass();
  $newgroup->name = format_string($request->groupname);
  $newgroup->description = format_text($request->description, FORMAT_HTML);
  $newgroup->courseid = SITEID;
  $newgroup->id = groups_create_group($newgroup);

  if (!$newgroup->id) {
    throw new Exception('Failed to create site-level group');
  }

  // Notify the requestor about the approval
  $user = $DB->get_record('user', ['id' => $request->userid], '*', MUST_EXIST);

  $message = new \core\message\message();
  $message->component = 'moodle';
  $message->name = 'instantmessage';
  $message->userfrom = \core_user::get_support_user();
  $message->userto = $user;
  $message->subject = "Your Proposed Group Request Approved";
  $message->fullmessage = "Congratulations! Your proposed group '{$newgroup->name}' has been approved and created.";
  $message->fullmessageformat = FORMAT_PLAIN;
  $message->fullmessagehtml = "Congratulations! Your proposed group <strong>{$newgroup->name}</strong> has been approved and created.";
  $message->smallmessage = "Your group request '{$newgroup->name}' has been approved.";
  $message->notification = 1;

  message_send($message);

  echo json_encode([
    'success' => true,
    'message' => 'Group created and request approved successfully. Notification sent to the user.',
    'groupid' => $newgroup->id
  ]);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500);
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
