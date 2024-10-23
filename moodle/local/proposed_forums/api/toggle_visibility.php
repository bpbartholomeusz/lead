<?php
// toggle_visibility.php
require_once('../../../config.php');
require_login(); // Ensure the user is logged in
$context = context_system::instance();

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the user has sufficient permissions to manage the site or view participants
if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied']);
  exit;
}

try {
  // Ensure the request method is POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405); // 405 Method Not Allowed
  }

  // Get the JSON data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Validate the input data
  if (!isset($data['forumid']) || !isset($data['visible'])) {
    throw new Exception('Invalid request data', 400); // Bad Request
  }

  $forumid = (int) $data['forumid']; // Cast the forum ID to an integer
  $visible = (int) $data['visible']; // Cast the visibility status to an integer

  global $DB;

  // Check if the forum exists
  if (!$DB->record_exists('forum', ['id' => $forumid])) {
    throw new Exception('Forum not found', 404); // 404 Not Found
  }

  // Update the visibility status of the forum
  $DB->set_field('forum', 'visible', $visible, ['id' => $forumid]);

  // Return a success message
  echo json_encode(['success' => true, 'message' => 'Forum visibility updated successfully']);
} catch (Exception $e) {
  // Return an error message with the appropriate HTTP status code
  http_response_code($e->getCode() ?: 500); // Default to 500 Internal Server Error
  echo json_encode(['error' => $e->getMessage()]);
}
