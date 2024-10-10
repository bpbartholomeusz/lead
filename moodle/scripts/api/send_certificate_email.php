<?php
// Include Moodle config file to initialize Moodle environment.
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/moodlelib.php');
require_once($CFG->libdir . '/datalib.php');

// Ensure the user is logged in and has admin rights
require_login();
if (!is_siteadmin()) {
  die('Access denied. You must be an admin to access this page.');
}

// Get POST parameters
$userid = required_param('userid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);

// Fetch the user from the database
$user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
if (!$user) {
  die('User not found');
}

try {
  // Fetch course module information for the Custom Certificate
  $cm = get_coursemodule_from_id('customcert', $cmid);
  if (!$cm) {
    throw new moodle_exception('Invalid course module ID.');
  }

  // Fetch the certificate record
  $certificate = $DB->get_record('customcert', array('id' => $cm->instance), '*', MUST_EXIST);
  if (!$certificate) {
    throw new moodle_exception('Certificate not found.');
  }

  // Issue the certificate
  \mod_customcert\certificate::issue_certificate($certificate->id, $userid);

  // Confirm that the certificate was issued and recorded
  if (!$DB->record_exists('customcert_issues', array('userid' => $userid, 'customcertid' => $certificate->id))) {
    throw new moodle_exception('Failed to issue certificate.');
  }

  // Customize the email subject and message
  $subject = 'Your Certificate from The LEAD Mindset Experience';
  $html_message = 'Dear ' . fullname($user) . ',<br><br>';
  $html_message .= 'Congratulations! Your certificate has been issued. You can view and download your certificate by clicking the link below:<br><br>';
  $html_message .= '<a href="' . $CFG->wwwroot . '/mod/customcert/my_certificates.php?userid=' . $userid . '">View Certificate</a><br><br>';
  $html_message .= 'Best regards,<br>The LEAD Team';

  // Send the email using Moodle's email function
  $email_success = email_to_user($user, get_admin(), $subject, strip_tags($html_message), $html_message);

  // Return JSON response
  if ($email_success) {
    echo json_encode(array('status' => 'success', 'message' => 'Email sent successfully to ' . $user->email));
  } else {
    throw new moodle_exception('Failed to send email.');
  }
} catch (Exception $e) {
  // Return JSON error response
  echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
  error_log('Certificate Email Error: ' . $e->getMessage());
  http_response_code(500); // Internal Server Error
}
