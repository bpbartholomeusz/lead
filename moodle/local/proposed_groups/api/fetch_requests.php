<?php
require_once('../../../config.php');

require_login();
$context = context_system::instance();
if (!is_siteadmin()) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied: Administrator access required']);
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
      ORDER BY gr.status ASC
  ";

  $requests = $DB->get_records_sql($sql);

  // Prepare data for DataTables in JSON format
  $data = [];
  foreach ($requests as $request) {
    $data[] = [
      'id' => $request->id,
      'userid' => $request->userid, // Include the user ID for profile linking
      'user' => fullname($request),
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
