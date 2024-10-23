<?php
require_once('../../config.php');
require_once('utils.php'); // Include the utils file
$context = context_system::instance();
$PAGE->set_context($context);

try {
  // Make cmid (Course Module ID) a required parameter.
  $cmid = required_param('cmid', PARAM_INT);
  $userid = optional_param('userid', $USER->id, PARAM_INT);

  // Ensure the user is logged in.
  require_login();

  // Check if the required cmid is provided.
  if (empty($cmid)) {
    echo "Error: Course module ID is missing or invalid.";
    exit;
  }

  // Get the course module for the Custom Certificate.
  $cm = get_coursemodule_from_id('customcert', $cmid);
  if (!$cm) {
    echo "Error: Could not find the course module for this certificate.";
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
  if (!$certificate) {
    echo "Error: Could not find the certificate with the provided ID.";
    exit;
  }

  // Check if the user is viewing their own certificate or if they are an admin.
  if (($userid != $USER->id) && !$is_admin) {
    echo "Error: You are not authorized to view other students' certificates.";
    exit;
  }

  // Qualification Criteria for Stage 5 based on Stage 1-5 Completion.
  if (!$is_admin) {
    // Use should_show_enrollment_key or relevant logic to determine completion for each stage.
    $completed_stage_1 = $DB->record_exists_select(
      'course_completions',
      'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
      ['userid' => $userid, 'course' => 165]
    );

    $completed_stage_2 = should_show_enrollment_key($userid, [24, 25]);
    $completed_stage_3 = should_show_enrollment_key($userid, [44, 27]);
    $completed_stage_4 = should_show_enrollment_key($userid, [29, 30]);
    $has_completed_stage_5 = should_show_enrollment_key($userid, [53]);

    // Ensure the user has completed all stages 1-4.
    if (!$completed_stage_1 || !$completed_stage_2 || !$completed_stage_3 || !$completed_stage_4 || !$has_completed_stage_5) {
      echo "You do not qualify for the LEAD Champions Certificate. Please complete all required stages.";
      exit;
    }
  }

  // Get the certificate template.
  $template = $DB->get_record('customcert_templates', ['id' => $certificate->templateid], '*', MUST_EXIST);
  if (!$template) {
    echo "Error: Could not find the template for this certificate.";
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
