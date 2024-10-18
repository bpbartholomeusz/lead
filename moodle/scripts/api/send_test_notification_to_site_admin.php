<?php
// Include Moodle configuration
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/message/lib.php');

// Ensure the user is logged in
require_login();

// Set content type to JSON
header('Content-Type: application/json');

try {
  // Use the current logged-in user as the sender
  $fromuser = $USER;

  // Get the main site administrator (usually ID 2)
  $mainadmin = $DB->get_record('user', ['id' => 2], '*', MUST_EXIST);

  // Prepare the web notification
  $subject = 'Test Notification';
  $message = 'This is a test web notification sent to the main site administrator.';

  // Set up the notification for web only
  $eventdata = new \core\message\message();
  $eventdata->component         = 'moodle';
  $eventdata->name              = 'instantmessage';
  $eventdata->userfrom            = \core_user::get_support_user();
  $eventdata->userto            = $mainadmin;
  $eventdata->subject           = $subject;
  $eventdata->fullmessage       = $message;
  $eventdata->fullmessageformat = FORMAT_PLAIN;
  $eventdata->fullmessagehtml   = "<p>{$message}</p>";
  $eventdata->smallmessage      = $subject;
  $eventdata->notification      = 1; // Indicates a notification

  // Attempt to send the web notification
  message_send($eventdata);

  // Return success response
  echo json_encode(['success' => true, 'message' => 'Test web notification sent to the main site administrator.']);
} catch (dml_missing_record_exception $e) {
  // Return error if the admin is not found
  echo json_encode(['error' => 'Main site administrator not found.']);
} catch (Exception $e) {
  // Catch other exceptions and return a generic error message
  echo json_encode(['error' => 'An error occurred while sending the notification.', 'exception' => $e->getMessage()]);
}
