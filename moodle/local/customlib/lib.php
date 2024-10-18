<?php
// Ensure this script is only accessible within Moodle context
defined('MOODLE_INTERNAL') || die();

// Include the message library to enable sending notifications
require_once($CFG->dirroot . '/message/lib.php');

/**
 * Sends a web notification to the main site administrator.
 *
 * @param string $subject The subject of the notification.
 * @param string $message The message content of the notification.
 * @return array An array with success status and message or error details.
 */
function send_test_notification_to_admin($subject, $message)
{
  global $DB;

  try {
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
    $eventdata->fullmessage       = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml   = "<p>{$message}</p>";
    $eventdata->smallmessage      = $subject;
    $eventdata->notification      = 1;
    $eventdata->messageofficer    = 'web'; // Ensure web delivery

    // Send the notification
    message_send($eventdata);

    return ['success' => true, 'message' => 'Test web notification sent to the main site administrator.'];
  } catch (dml_missing_record_exception $e) {
    return ['error' => 'Main site administrator not found.'];
  } catch (Exception $e) {
    return ['error' => 'An error occurred while sending the notification.', 'exception' => $e->getMessage()];
  }
}
