<?php
// Include Moodle's config and login handling
require_once('../../../config.php');
require_login(); // Ensures the user is logged in
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

// Check if the user has sufficient permissions
if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied']);
  exit;
}

// Set the response content type to JSON
header('Content-Type: application/json');

try {
  // Ensure the request is a DELETE method
  if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    throw new Exception('Only DELETE requests are allowed', 405); // 405 Method Not Allowed
  }

  // Get the forum ID from the request, sanitizing it
  $data = json_decode(file_get_contents('php://input'), true);
  $forumid = $data['forumid'] ?? null;

  if (empty($forumid)) {
    throw new Exception('Invalid forum ID', 400);
  }


  global $DB;

  // Check if the forum exists
  if (!$DB->record_exists('forum', ['id' => $forumid])) {
    throw new Exception('Forum not found', 404); // 404 Not Found
  }


  // Check any additional conditions before allowing deletion
  // e.g., you could add checks for forum ownership, site-level forums, etc.

  // Delete the forum record
  $DB->delete_records('forum', ['id' => $forumid]);

  // Optionally clean up associated data like forum discussions
  $DB->delete_records('forum_discussions', ['forum' => $forumid]);

  // Return a success message
  echo json_encode(['success' => true, 'message' => 'Forum deleted successfully']);
} catch (Exception $e) {
  // Handle errors and return appropriate response
  http_response_code($e->getCode() ?: 500); // Set the HTTP code (500 as default)
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getCode() === 500 ? 'Internal Server Error' : ''
  ]);
}
