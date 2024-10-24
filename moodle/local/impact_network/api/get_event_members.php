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

// Get the required parameter (eventid) and validate it
$eventid = required_param('eventid', PARAM_INT);

try {
  global $DB;

  // Check if the event exists
  if (!$DB->record_exists('impact_events', ['id' => $eventid])) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Event not found.']);
    exit;
  }

  // Fetch event members
  $sql = "
        SELECT u.id, u.firstname, u.lastname, u.email
        FROM {impact_event_members} em
        JOIN {user} u ON u.id = em.userid
        WHERE em.eventid = :eventid
    ";
  $members = $DB->get_records_sql($sql, ['eventid' => $eventid]);

  // Prepare the response data
  $data = [];
  foreach ($members as $member) {
    $data[] = [
      'id' => $member->id,
      'fullname' => fullname($member),
      'email' => $member->email
    ];
  }

  echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch event members', 'details' => $e->getMessage()]);
}