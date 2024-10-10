<?php

// Include Moodle config file to initialize Moodle environment.
require_once(__DIR__ . '/../../config.php');

try {
  // Make cmid (Course Module ID) a required parameter.
  $cmid = required_param('cmid', PARAM_INT);  // Make this required instead of optional.
  $userid = optional_param('userid', $USER->id, PARAM_INT);  // Use the logged-in user if no user ID is provided.

  // Check if the required cmid is provided.
  if (empty($cmid)) {
    echo "Course module ID is missing or invalid.";
    exit;
  }

  // Ensure the user is logged in.
  if (!isloggedin()) {
    echo "You must be logged in to view the certificate.";
    exit;
  }
  require_login();

  // Get the course module for the Custom Certificate.
  $cm = get_coursemodule_from_id('customcert', $cmid);
  if (empty($cm)) {
    echo "Could not find the course module for this certificate.";
    exit;
  }

  // Get the context of the Custom Certificate.
  $context = context_module::instance($cm->id);

  // Check if the user is an admin or has manage capabilities.
  $is_admin = has_capability('moodle/site:config', $context);
  $can_view_all_certificates = has_capability('mod/customcert:manage', $context);

  // Ensure the certificate exists.
  $certificateid = $cm->instance;
  $certificate = $DB->get_record('customcert', ['id' => $certificateid], '*', MUST_EXIST);
  if (empty($certificate)) {
    echo "Could not find the certificate with the provided ID.";
    exit;
  }

  // Check if the user is viewing their own certificate or if they are an admin.
  if ($userid != $USER->id && !$is_admin) {
    echo "You are not authorized to view other students' certificates.";
    exit;
  }

  // Qualification Criteria:
  // Ensure the student has completed at least one course in both "Raise The Bar" and "Example of Excellence".
  if (!$is_admin) {
    // Check if the student has completed at least one course in "Raise The Bar" (Category ID: 81).
    $completed_raise_the_bar = $DB->get_field_sql(
      "SELECT COUNT(DISTINCT c.id)
            FROM {course} c
            JOIN {enrol} e ON e.courseid = c.id
            JOIN {user_enrolments} ue ON ue.enrolid = e.id
            JOIN {course_modules_completion} cmc ON cmc.coursemoduleid IN (
                SELECT id FROM {course_modules} WHERE course = c.id
            )
            WHERE ue.userid = :userid
              AND cmc.completionstate = 1
              AND c.category IN (SELECT id FROM {course_categories} WHERE parent = 81)",  // Parent category "Raise The Bar"
      ['userid' => $userid]
    );

    // Check if the student has completed at least one course in "Example of Excellence" (Category ID: 82).
    $completed_example_of_excellence = $DB->get_field_sql(
      "SELECT COUNT(DISTINCT c.id)
            FROM {course} c
            JOIN {enrol} e ON e.courseid = c.id
            JOIN {user_enrolments} ue ON ue.enrolid = e.id
            JOIN {course_modules_completion} cmc ON cmc.coursemoduleid IN (
                SELECT id FROM {course_modules} WHERE course = c.id
            )
            WHERE ue.userid = :userid
              AND cmc.completionstate = 1
              AND c.category IN (SELECT id FROM {course_categories} WHERE parent = 82)",  // Parent category "Example of Excellence"
      ['userid' => $userid]
    );

    if ($completed_raise_the_bar == 0 || $completed_example_of_excellence == 0) {
      echo "You do not qualify for the certificate. Please complete the required courses first.";
      exit;
    }
  }

  // Get the certificate template.
  $template = $DB->get_record('customcert_templates', ['id' => $certificate->templateid], '*', MUST_EXIST);
  if (empty($template)) {
    echo "Could not find the template for this certificate.";
    exit;
  }

  // If the certificate hasn't been issued yet, issue it.
  if (!$DB->record_exists('customcert_issues', ['userid' => $userid, 'customcertid' => $certificateid])) {
    \mod_customcert\certificate::issue_certificate($certificate->id, $userid);
  }

  // Generate and output the certificate as a PDF.
  $template_instance = new \mod_customcert\template($template);
  $template_instance->generate_pdf(false, $userid);

  exit;
} catch (moodle_exception $e) {
  // Handle known Moodle exceptions.
  echo 'Error: ' . $e->getMessage();
  error_log('Moodle Exception: ' . $e->getMessage());
  http_response_code(500); // Internal Server Error

} catch (Exception $e) {
  // Handle any other generic exceptions.
  echo 'An unexpected error occurred: ' . $e->getMessage();
  error_log('Unexpected Exception: ' . $e->getMessage());
  http_response_code(500); // Internal Server Error
}

exit;
