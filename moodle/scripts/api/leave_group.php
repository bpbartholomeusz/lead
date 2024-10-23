<?php
// leave_group.php

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

  // Check if the user is a member of the group
  $ismember = $DB->record_exists('groups_members', [
    'groupid' => $groupid,
    'userid' => $USER->id
  ]);

  if (!$ismember) {
    throw new Exception('You are not a member of this group', 409);
  }

  // Remove the user from the group
  $DB->delete_records('groups_members', [
    'groupid' => $groupid,
    'userid' => $USER->id
  ]);

  // Find the group conversation
  $conversation = $DB->get_record('message_conversations', [
    'itemtype' => 'groups',  // Ensure 'groups' is the correct itemtype
    'itemid' => $groupid
  ]);

  if ($conversation) {
    // Remove the user from the conversation members table
    $DB->delete_records('message_conversation_members', [
      'conversationid' => $conversation->id,
      'userid' => $USER->id
    ]);
  }

  echo json_encode([
    'success' => true,
    'message' => 'Successfully left the group and removed from the conversation'
  ]);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Use the exception's HTTP code or 500 if none provided
  echo json_encode(['error' => $e->getMessage()]);
}
