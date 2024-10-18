<?php
// delete_forums.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Check admin access
if (!is_siteadmin()) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied: Administrator access required']);
  exit;
}

header('Content-Type: application/json');

try {
  global $DB;

  // Forum IDs to be deleted, excluding the one you want to keep (id=5)
  $forumIdsToDelete = [203];

  // Convert array to comma-separated string for the SQL IN clause
  list($inSql, $params) = $DB->get_in_or_equal($forumIdsToDelete, SQL_PARAMS_NAMED);

  // Delete forums from mdl_forum
  $DB->delete_records_select('forum', "id $inSql", $params);

  // Delete associated course modules
  $moduleId = $DB->get_field('modules', 'id', ['name' => 'forum'], MUST_EXIST);
  $DB->delete_records_select('course_modules', "module = :moduleid AND instance $inSql", ['moduleid' => $moduleId] + $params);

  echo json_encode([
    'success' => true,
    'message' => 'Specified forums deleted successfully, excluding forum ID 5',
    'deleted_forum_ids' => $forumIdsToDelete
  ]);
} catch (dml_exception $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Database error',
    'details' => $e->getMessage()
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Failed to delete forums',
    'details' => $e->getMessage()
  ]);
}
