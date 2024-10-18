<?php
// join_request.php

require_once('../../../config.php');
require_once($CFG->libdir . '/filelib.php'); // Moodle functions

require_login(); // Ensure the user is logged in
header('Content-Type: application/json'); // Set response type

try {
  // // Ensure only POST requests are allowed
  // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  //   throw new Exception('Only POST requests are allowed', 405);
  // }

  // Validate 'groupId' parameter directly from Moodle's required_param()
  $groupId = required_param('groupId', PARAM_INT);

  global $DB, $USER;
  $userId = $USER->id; // Get current user's ID

  // Check if the specified group exists
  if (!$DB->record_exists('groups', ['id' => $groupId])) {
    throw new Exception('The specified group does not exist', 404);
  }

  // Check if a pending join request already exists for this user and group
  $conditions = ['userid' => $userId, 'groupid' => $groupId, 'status' => 0];
  if ($DB->record_exists('group_requests', $conditions)) {
    echo json_encode(['success' => true, 'message' => 'Join request already exists']);
    exit;
  }

  // Insert the join request into the database
  $requestData = (object)[
    'userid' => $userId,
    'groupid' => $groupId,
    'status' => 0, // Assuming 0 indicates a pending request
    'timecreated' => time()
  ];
  $DB->insert_record('group_requests', $requestData);

  echo json_encode([
    'success' => true,
    'message' => 'Join request submitted successfully'
  ]);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Set appropriate HTTP response code
  echo json_encode([
    'error' => $e->getMessage()
  ]);
}
