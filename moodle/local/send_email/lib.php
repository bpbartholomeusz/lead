<?php

/**
 * Function to send an email message using Moodle's messaging system.
 *
 * @param int $fromuserid The ID of the user sending the email.
 * @param int $touserid The ID of the user receiving the email.
 * @param int $replytouserid The ID of the user to whom replies should be sent.
 * @param string $subject The subject of the email.
 * @param string $messagebody The body of the email (HTML or plain text).
 * @return bool True on success, False on failure.
 */
function local_send_email_send_message($fromuserid, $touserid, $replytouserid, $subject, $messagebody)
{
  global $DB;

  // Get the user records from the database.
  $fromuser = $DB->get_record('user', array('id' => $fromuserid));
  $touser = $DB->get_record('user', array('id' => $touserid));
  $replytouser = $DB->get_record('user', array('id' => $replytouserid));

  if (!$fromuser || !$touser || !$replytouser) {
    return false;  // Fail if any user does not exist.
  }

  // Create the message object.
  $message = new \core\message\message();
  $message->component = 'local_send_email';  // Component name.
  $message->name = 'notification';  // Type of the message (use 'notification' for custom).
  $message->userfrom = $fromuser;  // Sender.
  $message->userto = $touser;  // Recipient.
  $message->subject = $subject;  // Email subject.
  $message->fullmessage = $messagebody;  // Plain-text message body.
  $message->fullmessageformat = FORMAT_MARKDOWN;  // Message format (plain or markdown).
  $message->fullmessagehtml = format_text($messagebody, FORMAT_HTML);  // HTML message body.
  $message->smallmessage = '';  // Small message (optional).
  $message->notification = 1;  // Notification (email, not internal Moodle message).
  $message->replyto = $replytouser->email;  // Reply-to email address.

  // Send the message.
  return message_send($message);
}

/**
 * Callback function to extend the navigation.
 *
 * @param navigation_node $nav The navigation node object.
 * @param stdClass $course The course object.
 * @param context $context The context of the course.
 */
function local_send_email_extend_navigation_course($nav, $course, $context)
{
  // Add the 'Send Email' link to the course navigation.
  if (has_capability('local/send_email:send', $context)) {
    $url = new moodle_url('/local/send_email/index.php');
    $nav->add(get_string('sendemail', 'local_send_email'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/email', ''));
  }
}
