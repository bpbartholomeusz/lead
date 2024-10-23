<?php
require_once('../../config.php');
require_login();
global $DB, $USER;

// Check if the current user is an admin
if (!is_siteadmin()) {
    // If the user is not an admin, throw an error
    throw new moodle_exception('nopermission', 'error', '', null, 'You do not have permission to perform this action.');
}

// Get event ID and user ID from the GET parameters
$eventid = required_param('eventid', PARAM_INT);
$userid = required_param('userid', PARAM_INT);

// Check if the user is registered for the event
$registered = $DB->record_exists('event_registrations', ['userid' => $userid, 'eventid' => $eventid]);

if ($registered) {
    // Unregister the user by deleting their record from the table
    $DB->delete_records('event_registrations', ['userid' => $userid, 'eventid' => $eventid]);

    echo json_encode(['status' => 'unregistered']);
} else {
    echo json_encode(['status' => 'not_registered']);
}


header("Location: " . $_SERVER['HTTP_REFERER']);
exit();