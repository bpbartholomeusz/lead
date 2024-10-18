<?php
// approve_request.php

require_once('../../../config.php');
require_once($CFG->libdir . '/filelib.php'); // For Moodle functions
require_once($CFG->dirroot . '/group/lib.php'); // For groups_add_member()

require_login(); // Ensure the user is logged in
$context = context_system::instance();

// Check that the user has the necessary capabilities (admin only)
if (!is_siteadmin()) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied: Administrator access required']);
  exit;
}

header('Content-Type: application/json'); // Set response type

try {
  // Only accept POST requests
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405);
  }

  // Get the request data from DELETE input
  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = isset($data['requestid']) ? (int)$data['requestid'] : null;

  if (!$requestId) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;

  // Check if the request exists
  $request = $DB->get_record('group_requests', ['id' => $requestId], '*', MUST_EXIST);

  // Approve the request by updating the status
  $request->status = 1; // Assuming 1 means "Approved" (adjust based on your schema)
  $DB->update_record('group_requests', $request);

  // Add the user to the group
  $groupid = $request->groupid;
  $userid = $request->userid;

  // Check if the group exists
  if (!$DB->record_exists('groups', ['id' => $groupid])) {
    throw new Exception('Group does not exist', 404);
  }

  // Add the user to the group
  if (!groups_add_member($groupid, $userid)) {
    throw new Exception('Failed to add user to the group');
  }

  echo json_encode(['success' => true, 'message' => 'Join request approved and user added to group successfully']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Set appropriate HTTP response code
  echo json_encode([
    'error' => $e->getMessage()
  ]);
}
