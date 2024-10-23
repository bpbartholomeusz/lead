<?php
// fetch_groups_by_name_prefix.php

require_once(__DIR__ . '/../../config.php');
require_login();
$context = context_system::instance();

header('Content-Type: application/json');

try {
  global $DB, $USER;

  // SQL query to get all site-level groups with names starting with '#'
  $sql = "SELECT *
            FROM {groups}
            WHERE courseid = 1
              AND name LIKE :nameprefix";

  // Execute the query with the name prefix parameter
  $groups = $DB->get_records_sql($sql, ['nameprefix' => '#%']);

  // Prepare data for JSON response
  $data = [];
  foreach ($groups as $group) {
    // Check if the current user is a member of the group
    $is_member = $DB->record_exists('groups_members', [
      'groupid' => $group->id,
      'userid' => $USER->id
    ]);

    $data[] = [
      'id' => $group->id,
      'name' => $group->name,
      'idnumber' => $group->idnumber,
      'description' => $group->description,
      'is_member' => $is_member,
      'show_button' => isloggedin() && !isguestuser()
    ];
  }

  echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch groups', 'details' => $e->getMessage()]);
}
