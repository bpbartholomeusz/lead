<?php
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

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);
$eventid = isset($input['eventid']) ? (int)$input['eventid'] : null;
$eventname = isset($input['eventname']) ? trim($input['eventname']) : null;
$eventdescription = isset($input['eventdescription']) ? trim($input['eventdescription']) : null;

// Validate input
if (is_null($eventid) || empty($eventname) || is_null($eventdescription)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Event ID, name, and description are required.']);
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

  // Prepare the event data
  $eventdata = new stdClass();
  $eventdata->id = $eventid;
  $eventdata->eventname = $eventname;
  $eventdata->eventdescription = $eventdescription;

  // Update the event in the database
  $DB->update_record('impact_events', $eventdata);

  echo json_encode(['success' => true, 'message' => 'Event updated successfully.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to update event', 'details' => $e->getMessage()]);
}
