<?php
// propose_group.php

require_once('../../../config.php');
require_once($CFG->dirroot . '/message/lib.php'); // Required for messaging

require_login(); // Ensure the user is logged in
header('Content-Type: application/json'); // Set response type to JSON

try {
  // // Only allow POST requests
  // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  //   throw new Exception('Only POST requests are allowed', 405);
  // }

  // Get and validate required and optional parameters
  $groupname = required_param('groupname', PARAM_TEXT); // Required group name parameter
  $description = optional_param('description', '', PARAM_TEXT); // Optional description
  $audience = optional_param('audience', '', PARAM_TEXT); // Optional audience

  // Add "#" prefix to the group name for uniqueness and branding
  $groupnameWithHash = '#' . $groupname;

  // Verify group name length, assuming maximum of 255 characters (including #)
  if (strlen($groupnameWithHash) > 255) {
    throw new Exception('Group name cannot exceed 255 characters', 400);
  }

  global $DB, $USER;
  $userid = $USER->id;

  // Check if the group name with "#" already exists in site-level groups
  if ($DB->record_exists('groups', ['name' => $groupnameWithHash])) {
    throw new Exception('A group with this name already exists', 409); // Conflict
  }

  // Check if there is already a pending request for the same group name by this user
  if ($DB->record_exists('proposed_groups_requests', [
    'groupname' => $groupnameWithHash,
    'userid' => $userid,
    'status' => 0 // Check for pending requests only
  ])) {
    throw new Exception('A request to create this group is already pending', 409); // Conflict
  }

  // Prepare data for insertion
  $requestData = (object) [
    'userid' => $userid,
    'groupname' => $groupnameWithHash,
    'description' => $description,
    'audience' => $audience,
    'timecreated' => time(),
    'status' => 0 // Pending status
  ];

  // Insert the proposed group request
  $DB->insert_record('proposed_groups_requests', $requestData);

  // Send a notification to the main administrator about the new group proposal
  try {
    $subject = 'New Group Proposal Submitted';
    $message = "A new group proposal has been submitted.\n\nGroup Name: {$groupnameWithHash}\nDescription: {$description}\nAudience: {$audience}";
    $link = "{$CFG->wwwroot}/local/proposed_groups/";
    $message .= "\nYou can review it here: <a href=\"{$link}\">Proposed Groups Page</a>";

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

  // Respond with success message
  echo json_encode(['success' => true, 'message' => 'New group proposal submitted successfully']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Set appropriate HTTP response code
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
