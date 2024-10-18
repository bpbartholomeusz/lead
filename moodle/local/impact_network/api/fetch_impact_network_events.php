<?php
// fetch_impact_network_events.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

if (!is_siteadmin()) {
  http_response_code(403);
  echo json_encode(['error' => 'Access denied']);
  exit;
}

header('Content-Type: application/json');

try {
  global $DB;

  // SQL query to fetch all events
  $sql = "
      SELECT id, eventname, eventdescription
      FROM {impact_events}
      ORDER BY id DESC
  ";

  $events = $DB->get_records_sql($sql);

  // Prepare data for JSON output
  $data = [];
  foreach ($events as $event) {
    $data[] = [
      'id' => $event->id,
      'eventname' => format_string($event->eventname),
      'eventdescription' => format_text($event->eventdescription),
    ];
  }

  echo json_encode(['data' => $data]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch events', 'details' => $e->getMessage()]);
}
