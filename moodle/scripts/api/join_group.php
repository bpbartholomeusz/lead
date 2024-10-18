<?php
// join_group.php

require_once(__DIR__ . '/../../config.php');
require_login(); // Ensure the user is logged in
header('Content-Type: application/json');

try {
  // Check if the request method is POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405);
  }

  // Get the group ID from the request body
  $input = json_decode(file_get_contents('php://input'), true);
  $groupid = isset($input['groupid']) ? (int)$input['groupid'] : null;

  if (empty($groupid)) {
    throw new Exception('Invalid or missing group ID', 400);
  }

  global $DB, $USER;

  // Check if the group exists
  if (!$DB->record_exists('groups', ['id' => $groupid])) {
    throw new Exception('Group does not exist', 404);
  }

  // Check if the user is already a member of the group
  $ismember = $DB->record_exists('groups_members', [
    'groupid' => $groupid,
    'userid' => $USER->id
  ]);

  if ($ismember) {
    throw new Exception('You are already a member of this group', 409);
  }

  // Add the user to the group
  $record = new stdClass();
  $record->groupid = $groupid;
  $record->userid = $USER->id;
  $record->timeadded = time();

  $DB->insert_record('groups_members', $record);

  echo json_encode(['success' => true, 'message' => 'Successfully joined the group']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Use the exception's HTTP code or 500 if none provided
  echo json_encode(['error' => $e->getMessage()]);
}
