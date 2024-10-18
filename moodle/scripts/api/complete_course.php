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
$courseid = optional_param('id', 0, PARAM_INT); // Default to 0 if not provided.
$userid = optional_param('userid', $USER->id, PARAM_INT); // Default to current user if not provided.

// Validation: Check if course ID is provided.
if (empty($courseid)) {
  echo 'Error: Course ID is required.';
  exit;
}

// Validation: Check if user ID is provided.
if (empty($userid)) {
  echo 'Error: User ID is required.';
  exit;
}

// Try to get the course.
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

// Ensure the user has access to the course.
require_login($courseid);

// Check if completion tracking is enabled for the course.
$completion = new completion_info($course);
if (!$completion->is_enabled()) {
  echo 'Error: Completion tracking is not enabled for this course.';
  exit;
}

// Fetch all course modules (activities and resources).
$modinfo = get_fast_modinfo($courseid);
$cms = $modinfo->get_cms();

// Loop through each course module and mark it as complete for the user.
foreach ($cms as $cm) {
  // Only mark activities as complete if completion tracking is enabled for the module.
  if ($completion->is_enabled($cm)) {
    $completion->update_state($cm, COMPLETION_COMPLETE, $userid);
  }
}

// Set up the page for output (if needed).
$PAGE->set_url('/local/complete_course_content.php', array('id' => $courseid));
$PAGE->set_title('Mark Course Content Complete');
$PAGE->set_heading('Mark Course Content Complete');

// Output header and confirmation message.
echo $OUTPUT->header();
echo $OUTPUT->notification('All course content has been marked as completed for user ID ' . $userid, 'notifysuccess');

// Output footer.
echo $OUTPUT->footer();
