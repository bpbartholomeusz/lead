<?php
// Include the necessary Moodle configuration and libraries
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/moodlelib.php');
require_once($CFG->libdir . '/datalib.php');

// Ensure the user is logged in and has admin rights
require_login();
if (!is_siteadmin()) {
  die('Access denied. You must be an admin to access this page.');
}

// Get the userid from the URL or form submission
$userid = required_param('userid', PARAM_INT);

// Fetch the user from the database
$user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);

if (!$user) {
  die('User not found');
}

// Define placeholders for dynamic values
$first_name = $user->firstname;
$username = $user->username;
$temporary_password = 'LEADmpac@123'; // Replace with actual logic if needed

// Customize the email subject
$subject = 'test subject';

// Customize the email body by replacing placeholders
$html_message = '
<div class="SCXW22167656 BCX8">
  <div class="OutlineElement Ltr SCXW22167656 BCX8">
    <p class="Paragraph SCXW22167656 BCX8"><span>Dear ' . $first_name . ',</span></p>
    <p class="Paragraph SCXW22167656 BCX8"><span>We are excited to inform you that we have successfully migrated your account to our new Learning Management System Moodle, which is now live! This platform has been designed to offer you an enhanced learning experience and provide access to updated resources and features.</span></p>
  </div>
  <div class="OutlineElement Ltr SCXW22167656 BCX8">
    <p class="Paragraph SCXW22167656 BCX8"><strong>How to Access the New Platform:</strong></p>
  </div>
  <div class="OutlineElement Ltr SCXW22167656 BCX8">
    <p class="Paragraph SCXW22167656 BCX8"><span>To log in and continue your personal growth journey, please use the following details:</span></p>
  </div>
  <ul>
    <li>Platform Link: <a href="https://moodle.leadcurriculum.cloud/" target="_blank">https://moodle.leadcurriculum.cloud/</a></li>
    <li>Username: ' . $username . '</li>
    <li>Temporary Password: ' . $temporary_password . '</li>
  </ul>
  <div class="OutlineElement Ltr SCXW22167656 BCX8">
    <p class="Paragraph SCXW22167656 BCX8"><strong>Important: Change Your Password</strong></p>
    <p class="Paragraph SCXW22167656 BCX8">For security purposes, we strongly recommend changing your password immediately after logging in. You can do this by following these steps:</p>
    <ol>
      <li>Log in using the credentials provided above.</li>
      <li>Click <a href="https://moodle.leadcurriculum.cloud/login/change_password.php?id=1" target="_blank">here</a> and follow the on-screen instructions to change your password.</li>
    </ol>
  </div>
  <div class="OutlineElement Ltr SCXW22167656 BCX8">
    <p>If you encounter any issues or need assistance, please feel free to reach out to our support team at <a href="mailto:info@leadcurriculum.org">info@leadcurriculum.org</a> or call +44 (0) 7930 596193.</p>
    <p>We look forward to supporting your continued success on The LEAD Mindset journey to greatness which lies on the other side of service to others!</p>
    <p>Remember, Feeling Good is good for business &amp; life!</p>
  </div>
  <div>
    <p>Warm regards,<br><strong>Dennise Hilliman</strong><br>CEO @ Lead Curriculum</p>
  </div>
  <div>
    <a href="https://moodle.leadcurriculum.cloud/assets/Updated%20Video_V7.mp4" target="_blank">
      <img src="https://moodle.leadcurriculum.cloud/assets/welcome_email_thumbnail.png" alt="Welcome Video" width="720" height="390">
    </a>
  </div>
</div>
';

// Send the email using Moodle's email function
$email_success = email_to_user($user, get_admin(), $subject, strip_tags($html_message), $html_message);

// Provide feedback on whether the email was sent successfully
if ($email_success) {
  echo "Email sent successfully to " . $user->email;
  echo $html_message;
} else {
  echo "Failed to send email to " . $user->email;
}
