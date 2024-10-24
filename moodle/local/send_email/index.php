<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();

// Check for capabilities to ensure only authorized users can access this page.
if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  redirect(new moodle_url('/'));
  exit;
}

// Set up the page parameters and resources.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/send_email/index.php'));
$PAGE->set_title(get_string('sendemail', 'local_send_email'));
$PAGE->set_heading(get_string('sendemail', 'local_send_email'));
$PAGE->set_pagelayout('standard');

$PAGE->requires->css(new moodle_url('/local/send_email/send_email.scss'));

// Include Select2 CSS and JS files.
$PAGE->requires->css(new moodle_url('/local/send_email/assets/select2.css'));
$PAGE->requires->js(new moodle_url('/local/send_email/assets/select2.js'), true);

$PAGE->requires->js(new moodle_url('https://cdn.tiny.cloud/1/f9h8tk91bs7njcugeukqacydyf1adlyboxeo3ucxnn2y0d3s/tinymce/7/tinymce.min.js'), true);

// Output the header.
echo $OUTPUT->header();

// Fetch the list of users, sorted alphabetically by first name.
$users = $DB->get_records_sql('SELECT id, firstname, lastname FROM {user} ORDER BY firstname ASC');
?>

<!-- Bootstrap Form for sending an email -->
<div class="container mt-4 send-mail-wrapper">
  <form id="send-email-form" class="needs-validation" novalidate>

    <!-- To Field (Multiple Select) -->
    <div class="form-group row">
      <label for="to" class="col-sm-2 col-form-label">To:</label>
      <div class="col-sm-10">
        <select class="send-email-select" name="to[]" id="to" required multiple="multiple">
          <option value="" disabled>Select recipients</option>
          <?php
          foreach ($users as $user) {
            echo "<option value=\"{$user->id}\">{$user->firstname} {$user->lastname}</option>";
          }
          ?>
        </select>
        <div class="invalid-feedback">
          Please select at least one recipient.
        </div>
      </div>
    </div>

    <!-- Reply-To Options (No-Reply or Custom Email) -->
    <div class="form-group row">
      <label for="replyto-option" class="col-sm-2 col-form-label">Reply-To:</label>
      <div class="col-sm-10">
        <select class="form-control" name="replyto-option" id="replyto-option" required>
          <option value="no-reply">No-Reply</option>
          <option value="custom">Custom Email</option>
        </select>
        <div class="invalid-feedback">
          Please select a reply-to option.
        </div>
      </div>
    </div>

    <!-- Custom Reply-To Email (Hidden by default) -->
    <div id="custom-replyto" class="form-group row" style="display: none;">
      <label for="custom-replyto-email" class="col-sm-2 col-form-label">Custom Reply-To Email:</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" id="custom-replyto-email" name="custom-replyto-email" placeholder="Enter custom email">
        <div class="invalid-feedback">
          Please enter a valid email address.
        </div>
      </div>
    </div>

    <!-- Subject Field -->
    <div class="form-group row">
      <label for="subject" class="col-sm-2 col-form-label">Subject:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="subject" name="subject" required>
        <div class="invalid-feedback">
          Please enter a subject.
        </div>
      </div>
    </div>

    <!-- Message Body Field -->
    <div class="form-group row align-items-start">
      <label for="body" class="col-sm-2 col-form-label">Message Body:</label>
      <div class="col-sm-10">
        <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
        <div class="invalid-feedback">
          Please enter a message.
        </div>
      </div>
    </div>

    <!-- Submit Button with Welcome Email Checkbox beside it -->
    <div class="form-group row">
      <div class="col-sm-10 offset-sm-2 d-flex align-items-center justify-content-between">
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Send Email</button>
        <!-- Welcome Email Checkbox -->
        <div class="form-check mr-3">
          <input class="form-check-input" type="checkbox" id="welcome-email" name="welcome-email" value="1">
          <label class="form-check-label cursor-pointer" for="welcome-email">
            Welcome Email Template
          </label>
        </div>
      </div>
    </div>

  </form>
</div>

<script>
  $(document).ready(function() {
    // Initialize TinyMCE
    tinymce.init({
      selector: 'textarea#body',
      plugins: [
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
      ],
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      height: 300,
      menubar: false, // Optional: To show or hide the menubar
      branding: false, // Optional: To hide the "Powered by TinyMCE" branding
    });

    // Initialize Select2
    $('#to').select2({
      width: '100%',
      placeholder: 'Select recipients',
      allowClear: true,
    });

    // Show/Hide Custom Reply-To Email based on selection
    $('#replyto-option').change(function() {
      if ($(this).val() === 'custom') {
        $('#custom-replyto').show();
      } else {
        $('#custom-replyto').hide();
      }
    });

    // Trigger change to apply settings on page load
    $('#replyto-option').trigger('change');

    const TEMP_PASSWORD = 'LEADmpac@123';
    const PLATFORM_LINK = '<?php echo new moodle_url('/') ?>'
    const CHANGE_PASSWORD_LINK = '<?php echo new moodle_url('/login/change_password.php?id=1') ?>';

    // Welcome Email Template
    const welcomeTemplate = `
      <p>Dear [FIRST NAME],</p>
      <p>We are excited to inform you that we have successfully migrated your account to our new Learning Management System Moodle, which is now live! This platform has been designed to offer you an enhanced learning experience and provide access to updated resources and features.</p>
      <p><strong>How to Access the New Platform:</strong></p>
      <p>To log in and continue your personal growth journey, please use the following details:</p>
      <ul>
        <li>Platform Link: <a href="${PLATFORM_LINK}" target="_blank" rel="noreferrer noopener">${PLATFORM_LINK}</a></li>
        <li>Username: [USERNAME]</li>
        <li>Temporary Password: ${TEMP_PASSWORD}</li>
      </ul>
      <p><strong>Important:</strong> Change Your Password</p>
      <p>For security purposes, we strongly recommend changing your password immediately after logging in. You can do this by following these steps:</p>
      <ol>
        <li>Log in using the credentials provided above.</li>
        <li>Click <a href="${CHANGE_PASSWORD_LINK}" target="_blank" rel="noopener">here</a> and follow the on-screen instructions to change your password.</li>
      </ol>
      <p>If you encounter any issues or need assistance, please feel free to reach out to our support team at <a href="mailto:info@leadcurriculum.org">info@leadcurriculum.org</a> or +44 (0) 7930 596193.</p>
      <p>We look forward to supporting your continued success on The LEAD Mindset journey to greatness which lies on the other side of service to others!</p>
      <br>
      <a
      title="https://moodle.leadcurriculum.cloud/assets/Updated Video_V7.mp4"
      href="https://moodle.leadcurriculum.cloud/assets/Updated Video_V7.mp4" target="_blank" rel="noopener"><img
        class="img-fluid align-top" role="presentation"
        src="https://moodle.leadcurriculum.cloud/assets/welcome_email_thumbnail.png" alt="Welcome Video" width="720"
        height="390"></a>
      <br>
      <p>Warm Regards,<br><strong>Dennise Hilliman</strong><br>CEO @ Lead Curriculum</p>
    `;

    // Handle Welcome Email Template checkbox
    $('#welcome-email').change(function() {

      // Get the current content of the email body
      var currentBodyContent = tinymce.get('body').getContent();

      // Check if the user has already written something in the body
      if ($(this).is(':checked') && currentBodyContent.trim() !== '') {

        // Show confirmation dialog asking if they want to overwrite the content
        var confirmed = confirm('Using the Welcome Email Template will overwrite the current email body. Do you want to continue?');

        // If user confirms, set the template, otherwise uncheck the checkbox
        if (confirmed) {
          $('#subject').val('Welcome to LEAD Curriculum');
          tinymce.get('body').setContent(welcomeTemplate); // Set the welcome template if confirmed
        } else {
          $(this).prop('checked', false); // Uncheck the checkbox if user cancels
        }

      } else if ($(this).is(':checked')) {
        // If the checkbox is checked and there is no current content, set the template
        $('#subject').val('Welcome to LEAD Curriculum');
        tinymce.get('body').setContent(welcomeTemplate);

      } else {
        // If the checkbox is unchecked, clear both the subject and body
        $('#subject').val('');
        tinymce.get('body').setContent(''); // Clear the body content if unchecked
      }
    });


    // Handle form submission with AJAX
    $('#send-email-form').on('submit', function(e) {
      e.preventDefault(); // Prevent the default form submission

      if (confirm('Are you sure you want to send this email?')) {
        // Gather form data
        var formData = {
          to: $('#to').val(),
          replyto_option: $('#replyto-option').val(),
          subject: $('#subject').val(),
          body: tinymce.get('body').getContent(), // Use TinyMCE content
          welcome_email: $('#welcome-email').is(':checked') ? 1 : 0 // Check if welcome email is checked
        };

        // Add custom reply-to email if selected
        if (formData.replyto_option === 'custom') {
          formData.custom_replyto_email = $('#custom-replyto-email').val();
        }

        // Perform AJAX POST request
        $.ajax({
          url: '/local/send_email/api/send_mail.php',
          type: 'POST',
          data: formData,
          success: function(response) {
            alert('Email sent successfully!');
            $('#send-email-form')[0].reset();
            $('#custom-replyto').hide();
            $('#to').val([]).trigger('change');
          },
          error: function(xhr, status, error) {
            alert('An error occurred: ' + xhr.responseText);
          }
        });
      }
    });
  });
</script>

<?php
// Output the footer.
echo $OUTPUT->footer();
?>
