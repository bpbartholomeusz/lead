<?php
// leave_event.php

require_once('../../../config.php');
require_login();

header('Content-Type: application/json');

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);
$eventid = isset($input['eventid']) ? (int)$input['eventid'] : null;
$userid = $USER->id; // Get the current logged-in user

// Validate input
if (is_null($eventid)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Event ID is required.']);
  exit;
}

try {
  global $DB;

  // Check if the user is a member of the event
  $membership = $DB->get_record('impact_event_members', ['eventid' => $eventid, 'userid' => $userid], '*', IGNORE_MISSING);
  if (!$membership) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'You are not a member of this event.']);
    exit;
  }

  // Delete the user's membership record
  $DB->delete_records('impact_event_members', ['eventid' => $eventid, 'userid' => $userid]);

  echo json_encode(['success' => true, 'message' => 'You have successfully left the event.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to leave the event', 'details' => $e->getMessage()]);
}
