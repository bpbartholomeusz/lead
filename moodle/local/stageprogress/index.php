<?php
require_once('../../config.php');
require_once('utils.php'); // Include the utils file

// Redirect user to the login page if they are not logged in
if (!isloggedin() || isguestuser()) {
  redirect(new moodle_url('/login/index.php')); // Redirect to login page if not logged in
  exit;
}

// Get the user ID from the request or use the logged-in user's ID
$userid = optional_param('id', $USER->id, PARAM_INT);
$context = context_system::instance();

// Check if the user has admin privileges
$isadmin = has_capability('moodle/site:config', $context);

// Ensure only admins can access other users' progress
if (!$isadmin && $userid != $USER->id) {
  print_error('accessdenied', 'admin');
}

// Verify that the specified user exists and is not deleted
if (!$user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0))) {
  print_error('invaliduserid', 'error');
}

// Set up page information
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/stageprogress/index.php', array('id' => $userid)));
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Progress Overview");
$PAGE->set_heading("Progress Overview for " . fullname($user));

// Include external CSS for stage progress
$PAGE->requires->css('/local/stageprogress/stageprogress.css');

echo $OUTPUT->header();

// Prerequisites for LEAD Champion Certificate
$stage_criterias = [
  'Stage 1' => [(object) ['course_id' => 165]],  // Stage 1 has a single course completion criterion
  'Stage 2' => [(object) ['category_id' => 24], (object) ['category_id' => 25]], // Stage 2 requires completion of at least one course from these categories
  'Stage 3' => [(object) ['category_id' => 44], (object) ['category_id' => 27]], // Stage 3 requires at least one course from these categories
  'Stage 4' => [(object) ['category_id' => 29], (object) ['category_id' => 30]], // Stage 4 requires completion of specific categories
  'Stage 5' => [(object) ['category_id' => 53]]  // Stage 5 requires a category completion
];


// Define stages and their criteria with only IDs
$stages = [
  'Stage 1' => [
    'category_id' => 21,
    'certificates' => [],
  ],
  'Stage 2' => [
    'category_id' => 23,
    'criteria' => $stage_criterias['Stage 1'],
  ],
  'Stage 3' => [
    'category_id' => 26,
    'criteria' => $stage_criterias['Stage 2'],
  ],
  'Stage 4' => [
    'category_id' => 28,
    'criteria' => $stage_criterias['Stage 3'],
  ],
  'Stage 5' => [
    'category_id' => 31,
    'certificates' => [],
    'criteria' => $stage_criterias['Stage 1'],
  ]
];


// Checking completion for each stage
$has_completed_stage_1 = has_completed_stage($userid, $stage_criterias['Stage 1']);
$has_completed_stage_2 = has_completed_stage($userid, $stage_criterias['Stage 2']);
$has_completed_stage_3 = has_completed_stage($userid, $stage_criterias['Stage 3']);
$has_completed_stage_4 = has_completed_stage($userid, $stage_criterias['Stage 4']);
$has_completed_stage_5 = has_completed_stage($userid, $stage_criterias['Stage 5']);

// Lead Champion Certificate qualification check
$lead_champion_certificate_qualified = $has_completed_stage_1 && $has_completed_stage_2 && $has_completed_stage_3 && $has_completed_stage_4 && $has_completed_stage_5;


// Fetch user's progress and certificates for each stage
foreach ($stages as $stage => &$details) {
  $category_ids = get_all_child_categories($details['category_id']);
  $certificates = [];

  // Fetch all courses in the stage and its sub-categories and check completion
  $courses = $DB->get_records_sql("
        SELECT c.id, c.fullname
        FROM {course} c
        WHERE c.category IN (" . implode(',', $category_ids) . ")
    ");

  foreach ($courses as $course) {
    $is_completed = $DB->get_field('course_completions', 'timecompleted', ['userid' => $userid, 'course' => $course->id]);

    if ($is_completed) {
      $course_certs = $DB->get_records_sql("
                SELECT cm.id AS cmid
                FROM {customcert} cc
                JOIN {course_modules} cm ON cm.instance = cc.id
                JOIN {modules} m ON m.id = cm.module
                WHERE cc.course = :courseid
                  AND m.name = 'customcert'
            ", ['courseid' => $course->id]);

      foreach ($course_certs as $cert) {
        $certificates[] = (object) [
          'name' => $course->fullname,
          'url' => new moodle_url('/mod/customcert/view.php', ['id' => $cert->cmid])
        ];
      }
    }
  }

  $details['certificates'] = $certificates;
}

unset($details); // Clear reference

// Output the accordion structure with user progress and certificates
echo '<div class="accordion" id="stage-progress-accordion">';
foreach ($stages as $stage => $details) {
  $stageid = $details['category_id'];
  $category_name = get_category_name($details['category_id']);

  echo '<div class="card">';
  echo '<div class="card-header font-bold" id="heading' . $stageid . '" data-toggle="collapse" data-target="#collapse' . $stageid . '" aria-expanded="true" aria-controls="collapse' . $stageid . '"><strong>' . $stage . ': ' . $category_name . '</strong></div>';
  echo '<div id="collapse' . $stageid . '" class="collapse show" aria-labelledby="heading' . $stageid . '" data-parent="#accordionExample">';
  echo '<div class="card-body">';

  // Display LEAD Champion Certificate in Stage 5
  if ($stage == 'Stage 5') {
    echo '<div class="lead_champion_certificate ' . ($lead_champion_certificate_qualified ? 'qualified' : '') . '">';
    if ($lead_champion_certificate_qualified) {
      echo '<a href="/local/stageprogress/lead_champion_certificate.php?cmid=5335" class="text-white text-xxl">LEAD Champion Certificate</a>';
    } else {
      echo 'LEAD Champion Certificate';
    }
    echo '</div>';

    echo '<p><strong>Prerequisites to qualify for LEAD Champion Certificate:</strong></p>';
    echo '<ul>';
    display_criteria($userid, $stages['Stage 2']['criteria']);
    display_criteria($userid, $stages['Stage 3']['criteria']);
    display_criteria($userid, $stages['Stage 4']['criteria']);
    display_criteria($userid, [(object) ['category_id' => 29], (object) ['category_id' => 30]]); // Stage 4
    display_criteria($userid, [(object) ['category_id' => 53]]); // Stage 5
    echo '</ul>';
  }

  // Show criteria if there are any for the current stage
  if (!empty($details['criteria'])) {
    echo '<p><strong>Prerequisites to Enroll:</strong></p>';
    echo '<ul>';
    display_criteria($userid, $details['criteria']);
    echo '</ul>';
  }

  // Display certificates
  if (!empty($details['certificates'])) {
    echo '<p><strong>Completed Course Certificates:</strong></p>';
    echo '<ul class="certificates">';
    foreach ($details['certificates'] as $certificate) {
      echo '<li><i class="fas fa-award"></i><a href="' . $certificate->url . '" class="text-blue">' . $certificate->name . '</a></li>';
    }
    echo '</ul>';
  } else {
    echo '<p><strong>Course Certificates:</strong> No available certificates</p>';
  }

  echo '</div></div></div>';
}
echo '</div>';

echo $OUTPUT->footer();
