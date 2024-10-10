<?php
require_once('../../config.php');
require_once('utils.php'); // Include the utils file

require_login();

// Default to the logged-in user's ID
$userid = optional_param('id', $USER->id, PARAM_INT);
$context = context_system::instance();

// Check if the user is an admin
$isadmin = has_capability('moodle/site:config', $context);

// Ensure only admins can access others' progress
if (!$isadmin && $userid != $USER->id) {
  print_error('accessdenied', 'admin');
}

// Verify that the specified user exists in the Moodle database
if (!$user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0))) {
  print_error('invaliduserid', 'error');
}

// Set up page information
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/stageprogress/index.php', array('id' => $userid)));
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Progress Overview");
$PAGE->set_heading("Progress Overview for " . fullname($user));

// Link to the external CSS file
$PAGE->requires->css('/local/stageprogress/stageprogress.css');

echo $OUTPUT->header();

// Define stages with their criteria and enrollment keys
$stages = [
  'Stage 1: Introduction to the LEAD Mindset' => [
    'category_id' => 21,
    'certificates' => [],
  ],
  'Stage 2: Self-Literacy' => [
    'category_id' => 23,
    'criteria' => [
      (object) ['text' => 'Complete <b>Stage 1: stage 1: corporate executive: test course</b>', 'url' => '/course/view.php?id=165', 'course_id' => 165]
    ],
  ],
  'Stage 3: Social Literacy' => [
    'category_id' => 26,
    'criteria' => [
      (object) ['text' => 'Complete at least 1 course from <b>Stage 2 > Raise The Bar</b>', 'url' => '/course/index.php?categoryid=24', 'category_id' => 24],
      (object) ['text' => 'Complete at least 1 course from <b>Stage 2 > Example of Excellence</b>', 'url' => '/course/index.php?categoryid=25', 'category_id' => 25]
    ],
  ],
  'Stage 4: Purpose' => [
    'category_id' => 28,
    'criteria' => [
      (object) ['text' => 'Complete at least 1 course from <b>Stage 3 > Paradigm Shift</b>', 'url' => '/course/index.php?categoryid=29', 'category_id' => 29],
      (object) ['text' => 'Complete at least 1 course from <b>Stage 3 > Service Excellence and Legacy</b>', 'url' => '/course/index.php?categoryid=30', 'category_id' => 30]
    ],
  ],
  'Stage 5: Legacy' => [
    'category_id' => 31,
    'certificates' => [],
    'criteria' => [
      (object) ['text' => 'Complete <b>Stage 1: stage 1: corporate executive: test course</b>', 'url' => '/course/view.php?id=165', 'course_id' => 165]
    ],
  ]
];

// Prerequisite checks for each stage
$has_completed_stage_1 = $DB->record_exists_select(
  'course_completions',
  'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
  ['userid' => $userid, 'course' => 165]
);
$has_completed_stage_2 = should_show_enrollment_key($userid, [24, 25]);
$has_completed_stage_3 = should_show_enrollment_key($userid, [44, 27]);
$has_completed_stage_4 = should_show_enrollment_key($userid, [29, 30]);
$has_completed_stage_5 = should_show_enrollment_key($userid, [53]);

// Set prerequisites for each stage
$stages['Stage 2: Self-Literacy']['can_enroll'] = $has_completed_stage_1;
$stages['Stage 3: Social Literacy']['can_enroll'] = $has_completed_stage_2;
$stages['Stage 4: Purpose']['can_enroll'] = $has_completed_stage_3;
$stages['Stage 5: Legacy']['can_enroll'] = $has_completed_stage_1;

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
    $is_completed = $DB->get_field('course_completions', 'timecompleted', [
      'userid' => $userid,
      'course' => $course->id,
    ]);

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
echo '<div class="accordion" id="accordionExample">';
foreach ($stages as $stage => $details) {
  $stageid = $details['category_id'];

  echo '<div class="card">';
  echo '<div class="card-header font-bold" id="heading' . $stageid . '" data-toggle="collapse" data-target="#collapse' . $stageid . '" aria-expanded="true" aria-controls="collapse' . $stageid . '"><strong>' . $stage . '</strong></div>';
  echo '<div id="collapse' . $stageid . '" class="collapse show" aria-labelledby="heading' . $stageid . '" data-parent="#accordionExample">';
  echo '<div class="card-body">';

  // Show criteria if there are any for the stage
  if (!empty($details['criteria'])) {
    echo '<p><strong>Prerequisites to Enroll:</strong></p>';
    echo '<ul>';
    foreach ($details['criteria'] as $criterion) {
      $completion_status = isset($criterion->course_id)
        ? $DB->record_exists_select(
          'course_completions',
          'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
          ['userid' => $userid, 'course' => $criterion->course_id]
        )
        : should_show_enrollment_key($userid, [$criterion->category_id]);

      $status_text = $completion_status ? '<strong class="text-success">✔ Complete</strong>' : '<strong class="text-danger">✖ Incomplete</strong>';

      echo '<li><a href="' . $criterion->url . '" target="_blank" >' . $criterion->text . '</a> — ' . $status_text . '</li>';
    }
    echo '</ul>';
  }

  // Display certificates
  if (!empty($details['certificates']) || ($stage == 'Stage 5: Legacy' && $stages['Stage 5: Legacy']['can_enroll'])) {
    echo '<p><strong>Course Certificates:</strong></p>';
    echo '<ul>';

    // Display LEAD Champion Certificate
    if ($stage == 'Stage 5: Legacy' && $lead_champion_certificate_qualified) {
      echo '<li><a href="/local/stageprogress/lead_champion_certificate.php?cmid=5335" target="_blank" class="text-blue">LEAD Champion Certificate</a></li>';
    }

    foreach ($details['certificates'] as $certificate) {
      echo '<li><a href="' . $certificate->url . '" target="_blank" class="text-blue">' . $certificate->name . '</a></li>';
    }
    echo '</ul>';
  } else {
    echo '<p><strong>Course Certificates:</strong> No available certificates</p>';
  }

  echo '</div></div></div>';
}
echo '</div>';

echo $OUTPUT->footer();
