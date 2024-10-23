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

// Ensure the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
  http_response_code(405); // Method Not Allowed
  echo json_encode(['error' => 'Method not allowed. Please use DELETE.']);
  exit;
}

// Decode the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$eventid = isset($input['eventid']) ? (int)$input['eventid'] : null;

// Validate event ID
if (is_null($eventid)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Event ID is required.']);
  exit;
}

// Check if the event exists
if (!$DB->record_exists('impact_events', ['id' => $eventid])) {
  http_response_code(404); // Not Found
  echo json_encode(['error' => 'Event not found.']);
  exit;
}

try {
  // Delete the event
  $DB->delete_records('impact_events', ['id' => $eventid]);

  echo json_encode(['success' => true, 'message' => 'Event deleted successfully.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to delete event', 'details' => $e->getMessage()]);
}
