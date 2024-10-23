<?php
require_once('../../../config.php');
require_once($CFG->dirroot . '/message/lib.php'); // Include the message library directly
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

if (!isloggedin() || isguestuser()) {
  http_response_code(403); // Set the HTTP response code to 403 (Forbidden)
  echo json_encode(['error' => 'Please login first',]); // Return an access denied message
  exit;
}

header('Content-Type: application/json');

try {
  global $DB;

  // Get the current user's ID
  $userid = $USER->id;

  // Get event ID from the JSON request body
  $input = json_decode(file_get_contents('php://input'), true);
  $eventid = isset($input['eventid']) ? (int)$input['eventid'] : null;

  // Validate event ID
  if (is_null($eventid)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Event ID is required.']);
    exit;
  }

  // Check if the event exists
  if (!$DB->record_exists('impact_events', ['id' => $eventid])) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Event not found.']);
    exit;
  }

  // Check if the user is already an approved member of the event
  if ($DB->record_exists('impact_event_members', ['eventid' => $eventid, 'userid' => $userid])) {
    echo json_encode(['success' => false, 'message' => 'You are already a member of this event.']);
    exit;
  }

  // Check if the user already has a pending request for this event
  if ($DB->record_exists('impact_event_requests', ['eventid' => $eventid, 'userid' => $userid, 'status' => 0])) {
    echo json_encode(['success' => false, 'message' => 'Your registration request for this event is already pending.']);
    exit;
  }

  // Add a new registration with a pending status
  $registration = new stdClass();
  $registration->eventid = $eventid;
  $registration->userid = $userid;
  $registration->status = 0; // 0 = pending
  $registration->timecreated = time();

  // Insert the registration record and get the request ID
  $requestid = $DB->insert_record('impact_event_requests', $registration, true); // true to return the ID

  // Send notification to the main site administrator
  try {
    $eventname = $DB->get_field('impact_events', 'eventname', ['id' => $eventid]);
    $username = fullname($USER);

    // Prepare the message content
    $subject = 'New Registration request for ' . $eventname;
    $message = "A new event registration request has been submitted.\n\nEvent Name: {$eventname}\nUser: {$username}\n\n";
    $link = "{$CFG->wwwroot}/local/impact_network";
    $message .= "You can review the request here: <a href=\"{$link}\">Event Requests Page</a>";

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

    // Send the notification
    message_send($eventdata);
  } catch (Exception $e) {
    debugging('Failed to send notification: ' . $e->getMessage(), DEBUG_DEVELOPER);
  }

  echo json_encode(['success' => true, 'message' => 'Registration successful. Your request is pending approval.', 'requestid' => $requestid]);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to register for the event', 'details' => $e->getMessage()]);
}
