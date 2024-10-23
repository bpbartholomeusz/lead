<?php
// Required Moodle config and libraries
require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

// Set content type to JSON
header('Content-Type: application/json');

// Try to fetch forums from the database
try {
  global $DB;

  // SQL to get forums from site-level (usually courseid = 1 is for system context)
  $sql = "
        SELECT f.id, f.name AS forumname, f.intro AS description, f.timemodified, f.course
        FROM {forum} f
        WHERE f.course = :courseid
        ORDER BY f.id ASC
    ";

  // Fetch the site-level forums (courseid = 1 typically refers to the system context)
  $forums = $DB->get_records_sql($sql, ['courseid' => 1]);

  // Prepare data for JSON response
  $data = [];
  foreach ($forums as $forum) {
    $data[] = [
      'id' => $forum->id,
      'forumname' => format_string($forum->forumname),
      'description' => format_text(trim($forum->description)),
    ];
  }

  // Send the JSON response
  echo json_encode(['data' => $data]);
} catch (Exception $e) {
  // Handle error
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to fetch forums', 'details' => $e->getMessage()]);
}
