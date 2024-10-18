<?php
// fetch_impact_network_join_request.php

require_once('../../../config.php');
require_login();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied']);
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
    $fullname = fullname((object)[
      'firstname' => $request->firstname,
      'lastname' => $request->lastname
    ]);

    $data[] = [
      'id' => $request->id,
      'userid' => $request->userid,
      'user' => $fullname,
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
