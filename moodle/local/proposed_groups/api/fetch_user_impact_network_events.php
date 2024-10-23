<?php
require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

header('Content-Type: application/json');

$userid = $USER->id;

try {
  global $DB;

  // SQL query to fetch events, user registration status, and request IDs where applicable
  $sql = "
      SELECT e.id AS eventid, e.eventname, e.eventdescription,
          er.id AS requestid,
          CASE
              WHEN em.userid IS NOT NULL THEN 'approved'
              WHEN er.userid IS NOT NULL AND er.status = 0 THEN 'pending'
              ELSE 'not_registered'
          END AS registration_status
      FROM {impact_events} e
      LEFT JOIN {impact_event_requests} er ON e.id = er.eventid AND er.userid = :userid1
      LEFT JOIN {impact_event_members} em ON e.id = em.eventid AND em.userid = :userid2
  ";

  // Execute query with userid for both joins
  $events = $DB->get_records_sql($sql, ['userid1' => $userid, 'userid2' => $userid]);

  $data = [];
  foreach ($events as $event) {
    $data[] = [
      'eventid' => $event->eventid,
      'eventname' => $event->eventname,
      'eventdescription' => $event->eventdescription,
      'registration_status' => isloggedin() && !isguestuser() ? $event->registration_status : null,
      'requestid' => $event->requestid // Include requestid for cancel action
    ];
  }

  echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch events', 'details' => $e->getMessage()]);
}
