<?php

require_once('../../config.php'); // Include the Moodle config file.
require_once($CFG->libdir.'/accesslib.php'); // For role capabilities and checks.

require_login(); // Ensure the user is logged in.
@error_reporting(E_ALL | E_STRICT); // Report all PHP errors
@ini_set('display_errors', '1'); // Display errors
$CFG->debug = (E_ALL | E_STRICT); // Enable debugging
$CFG->debugdisplay = 1; // Show debug messages

global $USER, $DB;

// Check if the user is a student in a specific context, e.g., system or course context.
$context = context_system::instance(); // or context_course::instance($courseid) for course-specific check
$roles = get_user_roles($context, $USER->id);

print_r(is_siteadmin($USER->id));
$is_student = false;

foreach ($roles as $role) {
    if ($role->shortname === 'student') {
        $is_student = true;
        break;
    }
}

if(!is_siteadmin($USER->id)) {
    $is_student = true;
}
echo json_encode(array('is_student' => $is_student));