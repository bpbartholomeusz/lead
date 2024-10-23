<?php
// utils.php

require_once('../../config.php');

// Helper function to get all child category IDs recursively
function get_all_child_categories($parent_id)
{
  global $DB;
  $category_ids = [$parent_id];
  $child_categories = $DB->get_records('course_categories', ['parent' => $parent_id]);

  foreach ($child_categories as $child) {
    $category_ids = array_merge($category_ids, get_all_child_categories($child->id));
  }
  return $category_ids;
}

// Function to check if user has completed at least one course in each required sub-category
function should_show_enrollment_key($userid, $sub_category_ids)
{
  global $DB;
  foreach ($sub_category_ids as $parent_category) {
    $all_subcategories = get_all_child_categories($parent_category);
    $completed = $DB->record_exists_sql("
        SELECT 1
        FROM {course_completions} cc
        JOIN {course} c ON cc.course = c.id
        WHERE cc.userid = :userid
        AND cc.timecompleted IS NOT NULL
        AND c.category IN (" . implode(',', $all_subcategories) . ")
    ", ['userid' => $userid]);

    if (!$completed) {
      return false; // Return false if user hasn't completed at least one course in any required category
    }
  }
  return true;
}

// Define a function to check the completion status of a course or category
function get_completion_status($userid, $criterion)
{
  global $DB;

  // Check course completion if course_id is set
  if (isset($criterion->course_id)) {
    return $DB->record_exists_select(
      'course_completions',
      'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
      ['userid' => $userid, 'course' => $criterion->course_id]
    );
  }

  // Otherwise, check enrollment key status for a category
  return should_show_enrollment_key($userid, [$criterion->category_id]);
}

// Fetch course name using course ID
function get_course_name($course_id)
{
  global $DB;
  $course = get_course($course_id);
  return $course->fullname;
}

// Fetch category name using category ID
function get_category_name($category_id)
{
  $category = core_course_category::get($category_id);
  return $category->name;
}

// Generate dynamic URL for a course or category
function get_criterion_url($criterion)
{
  if (isset($criterion->course_id)) {
    return new moodle_url('/course/view.php', ['id' => $criterion->course_id]);
  }
  if (isset($criterion->category_id)) {
    return new moodle_url('/course/index.php', ['categoryid' => $criterion->category_id]);
  }
  return null; // In case neither course_id nor category_id is set
}

// Helper function to recursively get the top-level parent category
function get_top_level_category($category_id)
{
  global $DB;

  // Fetch the category record
  $category = $DB->get_record('course_categories', ['id' => $category_id], 'id, parent');

  // If the category has no parent, return it as the top-level category
  if ($category && $category->parent == 0) {
    return $category->id;
  }

  // Otherwise, recurse up the category tree
  return get_top_level_category($category->parent);
}

// Dynamic function to get stage by category or course from the database
function get_stage_by_course_or_category($id, $is_course = true)
{
  global $DB;

  // Define the stage categories mapping
  $stage_categories = [
    21 => 'Stage 1',
    23 => 'Stage 2',
    26 => 'Stage 3',
    28 => 'Stage 4',
    31 => 'Stage 5'
  ];

  // If it's a course, fetch its category first
  if ($is_course) {
    // Fetch the course and get its category_id
    $course = $DB->get_record('course', ['id' => $id], 'id, category');
    if ($course) {
      $top_level_category_id = get_top_level_category($course->category);
    }
  } else {
    // If it's a category, directly find the top-level category
    $top_level_category_id = get_top_level_category($id);
  }

  // Check if the top-level category maps to a stage in the stage categories
  if (isset($top_level_category_id) && isset($stage_categories[$top_level_category_id])) {
    return $stage_categories[$top_level_category_id]; // Return the stage name
  }

  return ''; // Return '' if no stage found
}


// Reusable function to display criteria
function display_criteria($userid, $criteria)
{
  foreach ($criteria as $criterion) {
    // Determine if the criterion is a course or category
    $is_course = isset($criterion->course_id);

    // Get the name of the course or category dynamically
    $name = $is_course ? get_course_name($criterion->course_id) : get_category_name($criterion->category_id);

    // Generate the URL dynamically for the course or category
    $url = get_criterion_url($criterion);

    // Check the completion status
    $completion_status = get_completion_status($userid, $criterion);
    $status_icon = $completion_status
      ? '<i class="fas fa-check-circle text-success"></i>'
      : '<i class="fas fa-times-circle text-danger"></i>';

    // Get the stage dynamically
    $stage = get_stage_by_course_or_category($is_course ? $criterion->course_id : $criterion->category_id, $is_course);

    // Output the formatted list item with FontAwesome icons and stage/course/category names
    echo '<li>' . $status_icon . '<a href="' . $url . '">'
      . ($is_course ? 'Complete ' : 'Complete at least 1 course from ')
      . '<b class="text-blue">' . $stage . ' > ' . $name . '</b></a></li>';
  }
}


// Function to check if the user has completed the required courses or categories for a stage
function has_completed_stage($userid, $criteria)
{
  global $DB;

  foreach ($criteria as $criterion) {
    // Check if it's a course or category and verify its completion
    if (isset($criterion->course_id)) {
      $completed = $DB->record_exists_select(
        'course_completions',
        'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
        ['userid' => $userid, 'course' => $criterion->course_id]
      );
    } elseif (isset($criterion->category_id)) {
      $completed = should_show_enrollment_key($userid, [$criterion->category_id]);
    }

    // If any criteria is not completed, return false
    if (!$completed) {
      return false;
    }
  }
  // All criteria completed
  return true;
}
