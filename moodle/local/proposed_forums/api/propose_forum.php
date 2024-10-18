<?php
// propose_forum.php

require_once('../../../config.php');
require_once($CFG->dirroot . '/message/lib.php'); // Include the message library directly
require_login();
header('Content-Type: application/json');

try {
  $forumname = required_param('forumname', PARAM_TEXT);
  $description = optional_param('description', '', PARAM_TEXT);
  $audience = optional_param('audience', '', PARAM_TEXT);

  if (strlen($forumname) > 255) {
    throw new Exception('Forum name cannot exceed 255 characters', 400);
  }

  global $DB, $USER;
  $userid = $USER->id;

  // Check if a forum with the same name already exists
  if ($DB->record_exists('forum', ['name' => $forumname])) {
    throw new Exception('A forum with this name already exists', 409);
  }

  // Check if there is a pending request for a forum with the same name by the same user
  if ($DB->record_exists('proposed_forums_requests', [
    'forumname' => $forumname,
    'userid' => $userid,
    'status' => 0
  ])) {
    throw new Exception('A request to create this forum is already pending', 409);
  }

  // Prepare data for the new forum proposal
  $requestData = (object) [
    'userid' => $userid,
    'forumname' => $forumname,
    'description' => $description,
    'audience' => $audience,
    'timecreated' => time(),
    'status' => 0
  ];

  // Insert the proposal into the database
  $DB->insert_record('proposed_forums_requests', $requestData);

  // Send notification to the main site administrator
  try {
    $subject = 'New Forum Proposal Submitted';
    $message = "A new forum proposal has been submitted.\n\nForum Name: {$forumname}\nDescription: {$description}\nAudience: {$audience}\n";
    $link = "{$CFG->wwwroot}/local/proposed_forums/";
    $message .= "\nYou can review it here: <a href=\"{$link}\">Proposed Forums Page</a>";

    // Use the support user as the sender
    $fromuser = \core_user::get_support_user();
    // Get the main site administrator (typically ID 2)
    $mainadmin = $DB->get_record('user', ['id' => 2], '*', MUST_EXIST);

    // Set up the web notification
    $eventdata = new \core\message\message();
    $eventdata->component         = 'moodle';
    $eventdata->name              = 'instantmessage';
    $eventdata->userfrom          = $fromuser;
    $eventdata->userto            = $mainadmin;
    $eventdata->subject           = $subject;
    $eventdata->fullmessage       = strip_tags($message); // Plain text fallback
    $eventdata->fullmessageformat = FORMAT_HTML;
    $eventdata->fullmessagehtml   = "<p>" . nl2br($message) . "</p>";
    $eventdata->smallmessage      = $subject;
    $eventdata->notification      = 1;

    // Send the web notification
    message_send($eventdata);
  } catch (Exception $e) {
    // Log notification failure but do not stop execution
    debugging('Failed to send notification: ' . $e->getMessage(), DEBUG_DEVELOPER);
  }

  // Return success response
  echo json_encode(['success' => true, 'message' => 'New forum proposal submitted successfully']);
} catch (Exception $e) {
  // Set HTTP response code and return error message
  http_response_code($e->getCode() ?: 500);
  echo json_encode(['error' => $e->getMessage()]);
}
