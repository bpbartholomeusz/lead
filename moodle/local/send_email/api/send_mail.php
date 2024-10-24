<?php
require_once('../../../config.php');
require_login(); // Ensure the user is logged in
$context = context_system::instance(); // Get the system context
require_once($CFG->libdir . '/moodlelib.php'); // Load Moodle core functions, including email_to_user()

// Set to unlimited execution time (or set a higher value as needed)
set_time_limit(0);
ignore_user_abort(true); // Continue executing even if user disconnects

// Set the page context and check capabilities
$PAGE->set_context($context);

// Set the response content type to JSON
header('Content-Type: application/json');

try {
  // Get the form data from the POST request.
  $toids = required_param_array('to', PARAM_INT);  // Multiple recipients in 'to[]'.
  $replyto_option = required_param('replyto_option', PARAM_TEXT);  // 'no-reply' or 'custom'.
  $subject = required_param('subject', PARAM_TEXT);
  $body = required_param('body', PARAM_RAW); // Keep the HTML tags intact
  $welcome_email = optional_param('welcome_email', 0, PARAM_INT); // Default to 0 if not set

  // Get the user object for the current user (who is sending the email).
  $fromuser = $USER;  // Sender is the logged-in user.

  // Determine the "Reply-To" email address and name.
  if ($replyto_option == 'custom') {
    $replyto_email = required_param('custom-replyto-email', PARAM_EMAIL);
    $replyto_name = "Custom Reply Name";  // Optional, you can add a name for the reply-to address
  } else {
    $replyto_email = 'no-reply@example.com';  // Predefined no-reply address.
    $replyto_name = "Do not reply";          // The name shown for no-reply.
  }

  // Initialize a success and error tracker for responses
  $success_emails = [];
  $failed_emails = [];

  // Loop through all the recipient IDs and send individual emails
  foreach ($toids as $toid) {
    // Retrieve user object with all necessary name fields
    $touser = $DB->get_record('user', array('id' => $toid), 'id, email, firstname, lastname, username, mailformat');

    // If user or email is missing, skip the user
    if (!$touser || empty($touser->email)) {
      $failed_emails[] = "User ID {$toid} not found or has no email address.";
      continue; // Skip to the next recipient
    }

    // Prepare the HTML email content
    $html_body = "<html><body>{$body}</body></html>";

    // Check if it's a welcome email and replace placeholders
    if ($welcome_email == 1) {
      $html_body = str_replace('[FIRST NAME]', $touser->firstname, $html_body);
      $html_body = str_replace('[USERNAME]', $touser->username, $html_body);
    }

    // Force HTML email if needed, based on mailformat (1 = HTML, 0 = plain-text)
    if (!isset($touser->mailformat) || $touser->mailformat != 1) {
      // Force HTML email even if the user prefers plain-text emails
      $touser->mailformat = 1;  // Force HTML format
    }

    // Attempt to send the email to the current recipient
    $email_success = email_to_user(
      $touser,                             // Recipient user object.
      $fromuser,                           // Sender user object.
      $subject,                            // Email subject.
      $body,                               // Plain-text message body.
      $html_body,                          // HTML version of the message (with proper HTML tags).
      '',                                  // Attachment (optional).
      '',                                  // Attachment name (optional).
      '',                                  // File attachment options (optional).
      '',                                  // User-specific options (optional).
      true,                                // Use reply-to.
      $replyto_email,                      // Custom reply-to email address.
      $replyto_name                        // Custom reply-to name (optional).
    );

    if ($email_success) {
      // Track successful emails
      $success_emails[] = $touser->email;
    } else {
      // Track failed emails if sending fails
      $failed_emails[] = $touser->email;
    }
  }

  // Return the result of the email sending operation
  if (count($failed_emails) > 0) {
    echo json_encode([
      'success' => false,
      'message' => 'Some emails failed to send.',
      'sent_to' => $success_emails,
      'failed_to_send' => $failed_emails
    ]);
  } else {
    echo json_encode([
      'success' => true,
      'message' => 'All emails sent successfully.',
      'sent_to' => $success_emails,
      'html_body' => $html_body
    ]);
  }
} catch (Exception $e) {
  // Handle any exceptions and return an error response
  http_response_code(500);
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
