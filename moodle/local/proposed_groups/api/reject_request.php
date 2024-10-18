<?php
// reject_request.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied']);
  exit;
}

header('Content-Type: application/json');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405);
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = $data['requestid'] ?? null;

  if (!$requestId) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;

  // Retrieve the request and check if it exists
  $request = $DB->get_record('proposed_groups_requests', ['id' => $requestId], '*', MUST_EXIST);

  // Update the status to 'Rejected' (assuming status 2 indicates rejection)
  $request->status = 2;
  if (!$DB->update_record('proposed_groups_requests', $request)) {
    throw new Exception('Failed to update request status in the database');
  }

  // Retrieve the user who proposed the group
  $user = $DB->get_record('user', ['id' => $request->userid], '*', MUST_EXIST);

  // Send a notification to the user informing them about the rejection
  $message = new \core\message\message();
  $message->component = 'moodle';
  $message->name = 'instantmessage';
  $message->userfrom = \core_user::get_support_user();
  $message->userto = $user;
  $message->subject = "Your Proposed Group Request Rejected";
  $message->fullmessage = "Unfortunately, your proposed group '{$request->groupname}' has been rejected.";
  $message->fullmessageformat = FORMAT_PLAIN;
  $message->fullmessagehtml = "Unfortunately, your proposed group <strong>{$request->groupname}</strong> has been rejected.";
  $message->smallmessage = "Your group request '{$request->groupname}' has been rejected.";
  $message->notification = 1;

  message_send($message);

  echo json_encode([
    'success' => true,
    'message' => 'Group request rejected and notification sent to the user'
  ]);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500);
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
