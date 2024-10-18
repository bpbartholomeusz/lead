<?php
// register_event.php

require_once('../../../config.php');
require_login();

header('Content-Type: application/json');

// Get the current user's ID
$userid = $USER->id;

// Get event ID from the JSON request body
$input = json_decode(file_get_contents('php://input'), true);
$eventid = isset($input['eventid']) ? (int)$input['eventid'] : null;

// Validate event ID
if (is_null($eventid)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Event ID is required.']);
  exit;
}

try {
  global $DB;

  // Check if the event exists
  if (!$DB->record_exists('impact_events', ['id' => $eventid])) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Event not found.']);
    exit;
  }

  // Check if the user is already an approved member of the event
  if ($DB->record_exists('impact_event_members', ['eventid' => $eventid, 'userid' => $userid])) {
    echo json_encode(['success' => false, 'message' => 'You are already a member of this event.']);
    exit;
  }

  // Check if the user already has a pending request for this event
  if ($DB->record_exists('impact_event_requests', ['eventid' => $eventid, 'userid' => $userid, 'status' => 0])) {
    echo json_encode(['success' => false, 'message' => 'Your registration request for this event is already pending.']);
    exit;
  }

  // Add a new registration with a pending status
  $registration = new stdClass();
  $registration->eventid = $eventid;
  $registration->userid = $userid;
  $registration->status = 0; // 0 = pending
  $registration->timecreated = time();

  $DB->insert_record('impact_event_requests', $registration);

  echo json_encode(['success' => true, 'message' => 'Registration successful. Your request is pending approval.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to register for the event', 'details' => $e->getMessage()]);
}
