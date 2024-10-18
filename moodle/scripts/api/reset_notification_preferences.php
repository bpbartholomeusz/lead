<?php
// Include Moodle configuration
require_once(__DIR__ . '/../../config.php');

// Ensure the user is logged in
require_login();

// Set content type to JSON
header('Content-Type: application/json');

// Get the current user ID
$userid = $USER->id;

// Reset the current user's notification preferences to default
try {
  $DB->delete_records('user_preferences', ['userid' => $userid]);
  echo json_encode(['success' => true, 'message' => 'Your notification preferences have been reset successfully.']);
} catch (Exception $e) {
  echo json_encode(['error' => 'Failed to reset your notification preferences.']);
}
