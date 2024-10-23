<?php
// Include Moodle config and required login handling
require_once('../../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/mod/forum/lib.php');

require_login(); // Ensure the user is logged in
$context = context_system::instance(); // Get the system context

// Set the page context and check capabilities
$PAGE->set_context($context);

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the user has the necessary permissions to add a forum
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
  if (!isset($data['forumname']) || !isset($data['forumdescription'])) {
    throw new Exception('Invalid request data. forumname and forumdescription are required.', 400); // Bad Request
  }

  // Sanitize the inputs
  $forumname = trim($data['forumname']);
  $forumdescription = trim($data['forumdescription']);

  // Ensure that the forum name and description are not empty
  if (empty($forumname) || empty($forumdescription)) {
    throw new Exception('Forum name and description cannot be empty.', 400); // Bad Request
  }

  global $DB;

  // Prepare the course module entry
  $mod = new stdClass();
  $mod->course = SITEID; // Site-level forum, courseid = 1
  $mod->module = $DB->get_field('modules', 'id', ['name' => 'forum']); // Get the module ID for forums
  $mod->instance = 0; // Set temporarily, will update after forum creation
  $mod->section = 0; // General section
  $mod->visible = 1; // Visible on the course page
  $mod->visibleoncoursepage = 1;
  $mod->added = time(); // Set current time
  $mod->id = add_course_module($mod); // Add the course module entry

  if (!$mod->id) {
    throw new Exception('Failed to create course module entry');
  }

  // Create the forum instance
  $forum = new stdClass();
  $forum->course = SITEID; // Site-level forum
  $forum->type = 'news'; // Type of forum (e.g., news, general)
  $forum->name = $forumname; // Forum name from input
  $forum->intro = $forumdescription; // Forum description from input
  $forum->introformat = FORMAT_HTML; // HTML format for the description
  $forum->forcesubscribe = 2; // Optional subscription (or adjust as needed)
  $forum->assessed = 0; // No grading
  $forum->timemodified = time(); // Current timestamp
  $forum->timecreated = time(); // Current timestamp
  $forum->coursemodule = $mod->id; // Link to the course module

  // Add the forum instance to the database
  $forum->id = forum_add_instance($forum);

  if (!$forum->id) {
    throw new Exception('Failed to create the forum instance');
  }

  // Update the course module with the correct instance ID
  $DB->set_field('course_modules', 'instance', $forum->id, ['id' => $mod->id]);

  // Add the course module to the section (for placement)
  course_add_cm_to_section($mod->course, $mod->id, $mod->section);

  // Return a success response
  echo json_encode(['success' => true, 'message' => 'Forum added successfully', 'forumid' => $forum->id]);
} catch (Exception $e) {
  // Handle any errors and return appropriate status and message
  http_response_code($e->getCode() ?: 500); // Set the HTTP status code (default 500)
  echo json_encode(['error' => $e->getMessage()]);
}
