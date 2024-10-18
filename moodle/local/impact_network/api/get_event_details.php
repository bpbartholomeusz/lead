<?php
// get_event_details.php

require_once('../../../config.php');
require_login();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied']);
  exit;
}

header('Content-Type: application/json');

// Retrieve and validate the event ID
$eventid = required_param('eventid', PARAM_INT);

try {
  global $DB;

  // Check if the event exists
  $event = $DB->get_record('impact_events', ['id' => $eventid], '*', MUST_EXIST);

  // Return the event details
  echo json_encode([
    'success' => true,
    'event' => [
      'id' => $event->id,
      'eventname' => $event->eventname,
      'eventdescription' => $event->eventdescription,
      'timecreated' => date('Y-m-d H:i:s', $event->timecreated)
    ]
  ]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to retrieve event details', 'details' => $e->getMessage()]);
}
