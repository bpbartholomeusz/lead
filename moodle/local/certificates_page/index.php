<?php

// Standard Moodle setup.
require_once(__DIR__ . '/../../config.php');
require_login();

// Ensure the user has the correct capabilities.
$context = context_system::instance();
require_capability('moodle/site:config', $context);  // Admin-only access

// Set up the page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/yourpluginname/index.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Custom Certificates Report");
$PAGE->set_heading("Custom Certificates Report");

echo $OUTPUT->header();

// Set the category IDs as variables.
$raise_the_bar_category_id = 81;
$example_of_excellence_category_id = 82;

// Concatenate the category IDs directly into the SQL query string.
$sql = "SELECT
            u.id AS userid,
            u.firstname,
            u.lastname,
            u.email,
            MAX(CASE WHEN c.category IN (SELECT id FROM {course_categories} WHERE parent = $raise_the_bar_category_id)
                     AND cc.timecompleted IS NOT NULL THEN 1 ELSE 0 END) AS completed_raise_the_bar,
            MAX(CASE WHEN c.category IN (SELECT id FROM {course_categories} WHERE parent = $example_of_excellence_category_id)
                     AND cc.timecompleted IS NOT NULL THEN 1 ELSE 0 END) AS completed_example_of_excellence
        FROM {user} u
        JOIN {user_enrolments} ue ON ue.userid = u.id
        JOIN {enrol} e ON e.id = ue.enrolid
        JOIN {course} c ON c.id = e.courseid
        LEFT JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = u.id
        JOIN {course_categories} subcat ON subcat.id = c.category
        WHERE subcat.parent IN ($raise_the_bar_category_id, $example_of_excellence_category_id) AND u.deleted = 0
        GROUP BY u.id, u.firstname, u.lastname, u.email
        ORDER BY u.lastname, u.firstname";

// Execute the query without needing to pass additional parameters.
$students = $DB->get_records_sql($sql);

// Set up the certificate ID.
$cmid = 5303;

// Display the table with an ID to use DataTables.
echo '<table id="studentsTable" class="generaltable">';
echo '<thead><tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Raise The Bar Completed</th><th>Example of Excellence Completed</th><th>Generate Certificate</th></tr></thead>';
echo '<tbody>';

// Loop through the students and populate the table.
foreach ($students as $student) {
  echo '<tr>';
  echo '<td>' . $student->userid . '</td>';
  echo '<td>' . $student->firstname . '</td>';
  echo '<td>' . $student->lastname . '</td>';
  echo '<td>' . $student->email . '</td>';
  echo '<td>' . ($student->completed_raise_the_bar ? '✔️' : '') . '</td>';
  echo '<td>' . ($student->completed_example_of_excellence ? '✔️' : '') . '</td>';

  // Only show the button if both conditions are met.
  if ($student->completed_raise_the_bar && $student->completed_example_of_excellence) {
    echo '<td><button class="btn btn-primary" onclick="sendCertificateEmail(' . $student->userid . ', ' . $cmid . ')">Send Certificate</button></td>';
  } else {
    echo '<td></td>';  // No button if the user doesn't qualify.
  }

  echo '</tr>';
}

echo '</tbody></table>';

// Include the sendCertificateEmail function as a script
?>
<script type="text/javascript">
  // JavaScript function to send the API request via POST
  async function sendCertificateEmail(userId, cmid) {
    if (!userId || !cmid) {
      alert('Invalid User ID or CMID');
      return;
    }

    const baseUrl = '<?php echo $CFG->wwwroot; ?>';
    const url = `${baseUrl}/scripts/api/send_certificate_email.php`;

    try {
      // Make a POST request to the PHP API
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `userid=${userId}&cmid=${cmid}` // POST parameters as part of the request body
      });

      // Parse the JSON response
      const data = await response.json();

      // Check if the request was successful
      if (data.status === 'success') {
        alert('Email sent successfully!');
        console.log('Certificate URL:', data.certificate_url); // Log the certificate URL for reference
      } else {
        alert('Error: ' + data.message); // Show error message if the API returned an error
      }
    } catch (error) {
      alert('An error occurred: ' + error.message); // Handle any fetch-related errors
    }
  }
</script>
<?php

echo $OUTPUT->footer();
