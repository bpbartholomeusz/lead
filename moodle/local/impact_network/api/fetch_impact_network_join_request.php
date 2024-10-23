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

try {
  global $DB;

  // Query to fetch join requests with user full name and event details
  $sql = "
      SELECT ir.id, ir.userid, u.firstname, u.lastname, e.eventname, ir.status, ir.timecreated
      FROM {impact_event_requests} ir
      JOIN {user} u ON u.id = ir.userid
      JOIN {impact_events} e ON e.id = ir.eventid
      ORDER BY ir.status ASC, ir.id DESC
  ";

  // Execute the query
  $requests = $DB->get_records_sql($sql);

  // Prepare data for JSON output
  $data = [];
  foreach ($requests as $request) {
    $data[] = [
      'id' => $request->id,
      'userid' => $request->userid,
      'user' => $request->firstname . ' ' . $request->lastname, // This now works since firstname and lastname are in the query
      'eventname' => format_string($request->eventname),
      'status' => $request->status,
      'timecreated' => userdate($request->timecreated),
    ];
  }

  echo json_encode(['data' => $data]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch join requests', 'details' => $e->getMessage()]);
}
