<?php
// Include Moodle config and login handling
require_once('../../../config.php');
require_login(); // Ensure the user is logged in
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the user has the necessary permissions to update the forum
if (!has_capability('moodle/site:config', $context) && !has_capability('mod/forum:manage', $context)) {
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

  // Validate required parameters
  if (!isset($data['forumid']) || !isset($data['forumname'])) {
    throw new Exception('Invalid request data. forumid and forumname are required.', 400); // Bad Request
  }

  // Sanitize the inputs
  $forumid = (int) $data['forumid'];
  $forumname = trim($data['forumname']);
  $description = trim($data['forumdescription']);

  // Ensure that the forum name and description are not empty
  if (empty($forumname)) {
    throw new Exception('Forum name cannot be empty.', 400); // Bad Request
  }

  global $DB;

  // Check if the forum exists
  $forum = $DB->get_record('forum', ['id' => $forumid], '*', MUST_EXIST);

  // Update the forum's name and description
  $forum->name = $forumname;
  $forum->intro = $description;
  $forum->timemodified = time(); // Update the modification timestamp

  // Update the forum record in the database
  $DB->update_record('forum', $forum);

  // Return a success response
  echo json_encode(['success' => true, 'message' => 'Forum updated successfully']);
} catch (Exception $e) {
  // Handle errors
  http_response_code($e->getCode() ?: 500); // Set the appropriate HTTP status code
  echo json_encode(['error' => $e->getMessage()]);
}
