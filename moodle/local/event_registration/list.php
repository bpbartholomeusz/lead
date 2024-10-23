<?php
require_once('../../config.php'); // Include the Moodle config file.
require_login(); // Ensure the user is logged in.

global $DB, $OUTPUT, $PAGE;
// Check if the current user is an admin
if (!is_siteadmin()) {
    // If the user is not an admin, throw an error
    echo $OUTPUT->header();
    echo html_writer::tag('h2', 'The page you are requesting is not availabe');
    echo $OUTPUT->footer();
    exit; // Stop execution if the event does not exist
}

// Get the event ID from the query parameters
$eventid = required_param('eventid', PARAM_INT); // Ensure it's an integer.

// Set up the page.
$PAGE->set_url(new moodle_url('/local/event_registration/list.php', array('eventid' => $eventid)));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Registered Users for Event ID: ' . $eventid);
$PAGE->set_heading('List of Registered Users for Event: ');

// Retrieve the event name based on the event ID
$event = $DB->get_record('event', ['id' => $eventid], 'name');

if ($event) {
    $eventname = $event->name;
    $PAGE->set_heading('List of Registered Users for Event: ' . $eventname); // Update heading with event name
} else {
    echo $OUTPUT->header();
    echo html_writer::tag('h2', 'Event not found');
    echo $OUTPUT->footer();
    exit; // Stop execution if the event does not exist
}

// Start outputting the page.
echo $OUTPUT->header();
echo html_writer::tag('h2', 'List of Registered Users for Event: ' . $eventname);

// Query to retrieve user registrations based on event ID, including email, full name, and username
$sql = "SELECT er.id, er.userid, er.eventid, er.timecreated, u.firstname, u.lastname, u.email, u.username
        FROM {event_registrations} er
        JOIN {user} u ON er.userid = u.id
        WHERE er.eventid = ?
        ORDER BY er.timecreated DESC";

$registrations = $DB->get_records_sql($sql, [$eventid]); // Execute the query with event ID as parameter.

// Check if there are any registrations.
if (!empty($registrations)) {
    // Display the table of events and users who registered.
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>Full Name</th><th>Username</th><th>Email</th><th>Registration Time</th><th>Action</th></tr></thead>';
    echo '<tbody>';
    foreach ($registrations as $registration) {
        $fullname = $registration->firstname . ' ' . $registration->lastname;
        $username = $registration->username;
        $email = $registration->email;
        $registrationtime = userdate($registration->timecreated);
        
        // Create the unregister button with confirmation
        echo "<tr>
                <td>{$fullname}</td>
                <td>{$username}</td>
                <td>{$email}</td>
                <td>{$registrationtime}</td>
                <td>
                    <button class='btn btn-danger' onclick='confirmUnregister({$registration->userid}, \"{$fullname}\", {$eventid})'>Unregister</button>
                </td>
              </tr>";
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No registrations found for this event.</p>'; // Update the message for no registrations found.
}

// JavaScript confirmation function
echo '<script>
function confirmUnregister(userId, userName, eventId) {
    if (confirm("Are you sure you want to unregister " + userName + "?")) {
        // Redirect to unregister.php with user ID and event ID as parameters
        window.location.href = "/local/event_registration/unregister.php?userid=" + userId + "&eventid=" + eventId;
    }
}
</script>';

// End the page.
echo $OUTPUT->footer();
