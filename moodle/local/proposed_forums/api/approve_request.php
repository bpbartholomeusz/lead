<?php
// approve_request.php

require_once('../../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/mod/forum/lib.php');

require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  http_response_code(403); // Set the HTTP response code to 403 (Forbidden)
  echo json_encode(['error' => 'Access denied']); // Return an access denied message
  exit;
}

header('Content-Type: application/json');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Only POST requests are allowed', 405);
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = $data['requestid'] ?? null;

  if (empty($requestId)) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB, $USER;

  // Retrieve the request and check if it exists and is pending
  $request = $DB->get_record('proposed_forums_requests', ['id' => $requestId], '*', MUST_EXIST);
  if ($request->status != 0) {
    throw new Exception('Request is not pending or has already been processed', 400);
  }

  // Approve the request
  $request->status = 1;
  $DB->update_record('proposed_forums_requests', $request);

  // Prepare the course module entry first
  $mod = new stdClass();
  $mod->course = SITEID;
  $mod->module = $DB->get_field('modules', 'id', ['name' => 'forum']);
  $mod->instance = 0; // Set temporarily, will update after forum creation
  $mod->section = 0; // General section
  $mod->visible = 1;
  $mod->visibleoncoursepage = 1;
  $mod->added = time();
  $mod->id = add_course_module($mod);

  if (!$mod->id) {
    throw new Exception('Failed to create course module entry');
  }

  // Prepare the forum instance, now including the course module ID
  $forum = new stdClass();
  $forum->course = SITEID;
  $forum->type = 'news'; // Announcement forum
  $forum->name = $request->forumname;
  $forum->intro = $request->description;
  $forum->introformat = FORMAT_HTML;
  $forum->forcesubscribe = 2;
  $forum->assessed = 0;
  $forum->timemodified = time();
  $forum->coursemodule = $mod->id; // Set the course module ID

  // Add the forum instance
  $forum->id = forum_add_instance($forum);
  if (!$forum->id) {
    throw new Exception('Failed to create the forum instance');
  }

  // Update the course module with the new forum instance ID
  $DB->set_field('course_modules', 'instance', $forum->id, ['id' => $mod->id]);

  // Check if the functions exist before calling them
  if (function_exists('course_add_cm_to_section')) {
    // Adding course module to section
    course_add_cm_to_section($mod->course, $mod->id, $mod->section);
  } else {
    throw new Exception('course_add_cm_to_section functions is missing');
  }

  // Notify the requestor about the approval
  $user = $DB->get_record('user', ['id' => $request->userid], '*', MUST_EXIST);

  $message = new \core\message\message();
  $message->component = 'moodle';
  $message->name = 'instantmessage';
  $message->userfrom = \core_user::get_support_user();
  $message->userto = $user;
  $message->subject = "Your Proposed Forum Request Approved";
  $message->fullmessage = "Congratulations! Your proposed forum '{$forum->name}' has been approved and created.";
  $message->fullmessageformat = FORMAT_PLAIN;
  $message->fullmessagehtml = "Congratulations! Your proposed forum <strong>{$newforum->name}</strong> has been approved and created.";
  $message->smallmessage = "Your forum request '{$newforum->name}' has been approved.";
  $message->notification = 1;

  message_send($message);

  echo json_encode(['success' => true, 'message' => 'Announcement forum created and request approved successfully']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500);
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
