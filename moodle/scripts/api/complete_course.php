<?php
// Moodle configuration and initialization.
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/completionlib.php');

// Ensure the user is logged in.
require_login();

// Check if the current user is an administrator.
if (!is_siteadmin()) {
  echo 'Error: Access denied. This page is only accessible to administrators.';
  exit;
}

// Get the course ID from the URL parameter.
$courseid = required_param('courseid', PARAM_INT); // Single course ID.
$userid_list = optional_param('userids', '', PARAM_RAW); // Comma-separated string of user IDs.

// Validation: Check if course ID is provided.
if (empty($courseid)) {
  echo 'Error: Course ID is required.';
  exit;
}

// Validation: Check if the list of user IDs is provided.
if (empty($userid_list)) {
  echo 'Error: List of user IDs is required.';
  exit;
}

// Convert the comma-separated string to an array of user IDs.
$userids_array = array_map('trim', explode(',', $userid_list));

// Validation: Check if the course exists.
if (!$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST)) {
  echo 'Error: Course ID ' . $courseid . ' does not exist.';
  exit;
}

// Ensure the user has access to the course.
require_login($courseid);

// Check if completion tracking is enabled for the course.
$completion = new completion_info($course);
if (!$completion->is_enabled()) {
  echo 'Error: Completion tracking is not enabled for this course.';
  exit;
}

// Fetch all course modules (activities and resources) for this course.
$modinfo = get_fast_modinfo($courseid);
$cms = $modinfo->get_cms();

// Loop through each user ID and process completion for each user.
foreach ($userids_array as $userid) {
  // Validation: Check if the user ID is valid and exists.
  if (!$user = $DB->get_record('user', array('id' => $userid), '*', IGNORE_MISSING)) {
    echo 'Error: User ID ' . $userid . ' does not exist. Skipping this user.<br>';
    continue; // Skip this user if they do not exist.
  }

  // Check if the user is enrolled in the course.
  $context = context_course::instance($courseid);
  if (!is_enrolled($context, $userid)) {
    echo 'Error: User ID ' . $userid . ' is not enrolled in course ID ' . $courseid . '. Skipping this user.<br>';
    continue; // Skip this user if they are not enrolled.
  }

  // Loop through each course module and mark it as complete for the user.
  foreach ($cms as $cm) {
    // Only mark activities as complete if completion tracking is enabled for the module.
    if ($completion->is_enabled($cm)) {
      $completion->update_state($cm, COMPLETION_COMPLETE, $userid);
    }
  }

  // Optional: Output success message for each user (can be commented out for clean redirection).
  // echo 'Success: All course content has been marked as completed for user ID ' . $userid . ' in course ID ' . $courseid . '.<br>';
}

// After processing all users, redirect to the progress report page.
$redirecturl = new moodle_url('/report/progress/index.php', array('course' => $courseid));
redirect($redirecturl);
