<?php
// reject_request.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  http_response_code(403); // Set the HTTP response code to 403 (Forbidden)
  echo json_encode(['error' => 'Access denied']); // Return an access denied message
  exit;
}

header('Content-Type: application/json');
try {
  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = $data['requestid'] ?? null;

  if (!$requestId) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;
  $request = $DB->get_record('proposed_forums_requests', ['id' => $requestId], '*', MUST_EXIST);
  $request->status = 2;
  $DB->update_record('proposed_forums_requests', $request);

  // Retrieve the user who proposed the forum
  $user = $DB->get_record('user', ['id' => $request->userid], '*', MUST_EXIST);

  // Send a notification to the user informing them about the rejection
  $message = new \core\message\message();
  $message->component = 'moodle';
  $message->name = 'instantmessage';
  $message->userfrom = \core_user::get_support_user();
  $message->userto = $user;
  $message->subject = "Your Proposed Forum Request Rejected";
  $message->fullmessage = "Unfortunately, your proposed forum '{$request->forumname}' has been rejected.";
  $message->fullmessageformat = FORMAT_PLAIN;
  $message->fullmessagehtml = "Unfortunately, your proposed forum <strong>{$request->forumname}</strong> has been rejected.";
  $message->smallmessage = "Your forum request '{$request->forumname}' has been rejected.";
  $message->notification = 1;

  message_send($message);

  echo json_encode(['success' => true, 'message' => 'Forum request rejected']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500);
  echo json_encode(['error' => $e->getMessage()]);
}
