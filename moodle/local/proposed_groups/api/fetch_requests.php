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

  // Fetch all proposed group requests with user and group data
  $sql = "
      SELECT gr.id, u.id AS userid, u.firstname, u.lastname, gr.groupname, gr.description, gr.audience, gr.status, gr.timecreated
      FROM {proposed_groups_requests} gr
      JOIN {user} u ON u.id = gr.userid
      ORDER BY gr.status ASC, gr.id DESC
  ";

  $requests = $DB->get_records_sql($sql);

  // Prepare data for DataTables in JSON format
  $data = [];
  foreach ($requests as $request) {
    $data[] = [
      'id' => $request->id,
      'userid' => $request->userid, // Include the user ID for profile linking
      'user' => $request->firstname . ' ' . $request->lastname,
      'groupname' => format_string($request->groupname),
      'description' => format_text($request->description),
      'audience' => format_string($request->audience),
      'status' => $request->status,
      'timecreated' => userdate($request->timecreated)
    ];
  }

  echo json_encode(['data' => $data]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch requests', 'details' => $e->getMessage()]);
}
