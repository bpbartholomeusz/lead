<?php
require_once('../../config.php');
require_login();
global $USER, $DB;

// Get event ID from the AJAX request
$eventid = required_param('eventid', PARAM_INT);

// Check if the user has already registered
$registered = $DB->record_exists('event_registrations', ['userid' => $USER->id, 'eventid' => $eventid]);

if (!$registered) {
    // Register the user
    $record = new stdClass();
    $record->userid = $USER->id;
    $record->eventid = $eventid;
    $record->timecreated = time();

    $DB->insert_record('event_registrations', $record);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'already_registered']);
}
