<?php
// delete_request.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

if (!isloggedin() || isguestuser()) {
  http_response_code(403); // Set the HTTP response code to 403 (Forbidden)
  echo json_encode(['error' => 'Please login first',]); // Return an access denied message
  exit;
}

header('Content-Type: application/json');

// Get the current user's ID
$userid = $USER->id;

// Get the request ID from the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$requestid = isset($input['requestid']) ? (int)$input['requestid'] : null;

// Validate the request ID
if (is_null($requestid)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Request ID is required.']);
  exit;
}

try {
  global $DB;

  // Check if the join request exists
  $request = $DB->get_record('impact_event_requests', ['id' => $requestid], 'id, userid', MUST_EXIST);

  // Check permissions: admin can delete any request, users can delete only their own requests
  if (!is_siteadmin() && $request->userid != $userid) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access denied. You can only delete your own requests.']);
    exit;
  }

  // Delete the join request
  $DB->delete_records('impact_event_requests', ['id' => $requestid]);

  echo json_encode(['success' => true, 'message' => 'Join request deleted successfully.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to delete join request', 'details' => $e->getMessage()]);
}
