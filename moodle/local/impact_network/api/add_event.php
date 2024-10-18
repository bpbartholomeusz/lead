<?php
// add_event.php

require_once('../../../config.php');
require_login();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied']);
  exit;
}

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); // Method Not Allowed
  echo json_encode(['error' => 'Method not allowed. Please use POST.']);
  exit;
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$eventname = isset($input['eventname']) ? trim($input['eventname']) : null;
$eventdescription = isset($input['eventdescription']) ? trim($input['eventdescription']) : '';

// Validate input
if (empty($eventname)) {
  http_response_code(400);
  echo json_encode(['error' => 'Event name is required.']);
  exit;
}

try {
  global $DB;

  // Prepare event data
  $eventdata = new stdClass();
  $eventdata->eventname = $eventname;
  $eventdata->eventdescription = $eventdescription;
  $eventdata->timecreated = time();

  // Insert the new event into the database
  $eventid = $DB->insert_record('impact_events', $eventdata);

  echo json_encode(['success' => true, 'eventid' => $eventid]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to add event', 'details' => $e->getMessage()]);
}
