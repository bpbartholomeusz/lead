<?php
require_once('../../config.php'); // Include Moodle config file.
require_login(); // Ensure the user is logged in.

global $DB, $USER;

// Get the event ID from the request
$eventid = optional_param('eventid', 0, PARAM_INT);

// Check if the user is already registered for this event
$registered = $DB->record_exists('event_registrations', array('userid' => $USER->id, 'eventid' => $eventid));

// Fetch event details to check the type
$event = $DB->get_record('event', array('id' => $eventid));

$event_type = isset($event->eventtype) ? $event->eventtype : '';

// Return the response as JSON
echo json_encode(array('registered' => $registered, 'event_type' => $event_type));
?>
