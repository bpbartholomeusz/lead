<?php
// utils.php

require_once($CFG->dirroot . '/config.php');

// Function to retrieve the top-level category (stage) of a course
function get_course_stage_category($courseid)
{
  global $DB;

  // Get the initial category ID of the course
  $course = $DB->get_record('course', ['id' => $courseid], 'category', MUST_EXIST);
  $current_category = $course->category;

  // Traverse up the category hierarchy to find the main category (no parent)
  while ($current_category) {
    $category = $DB->get_record('course_categories', ['id' => $current_category], 'id, parent');

    // If there's no parent, it's the top-level category.
    if ($category->parent == 0) {
      return $category->id; // Main category ID (Stage)
    }

    // Move up to the parent category
    $current_category = $category->parent;
  }

  return null;
}


// Function to determine if the prerequisites for the specified stage have been met
function has_met_stage_prerequisites($userid, $stage_category_id)
{
  global $DB;

  // Define criteria for each stage based on the prerequisite logic
  $stages = [
    'Stage 1: Introduction to the LEAD Mindset' => [
      'category_id' => 21,
    ],
    'Stage 2: Self-Literacy' => [
      'category_id' => 23,
      'criteria' => ['course_id' => 165] // Specific course completion check for Stage 1

    ],
    'Stage 3: Social Literacy' => [
      'category_id' => 26,
      'criteria' => ['category_ids' => [24, 25]] // Complete at least one course in specified categories

    ],
    'Stage 4: Purpose' => [
      'category_id' => 28,
      'criteria' => ['category_ids' => [44, 27]] // Complete at least one course in specified categories
    ],
    'Stage 5: Legacy' => [
      'category_id' => 31,
      'criteria' => ['course_id' => 165] // Specific course completion check for Stage 1
    ]
  ];

  // Locate the stage criteria based on the provided stage category ID
  foreach ($stages as $stage => $details) {
    if ($stage_category_id == $details['category_id']) {

      // If the stage has no criteria, prerequisites are considered met
      if (!isset($details['criteria'])) {
        return true;
      }

      // Check for a specific course completion requirement
      if (isset($details['criteria']['course_id'])) {
        return $DB->record_exists_select(
          'course_completions',
          'userid = :userid AND course = :course AND timecompleted IS NOT NULL',
          ['userid' => $userid, 'course' => $details['criteria']['course_id']]
        );
      }

      // Check for category-based completion requirements
      if (isset($details['criteria']['category_ids'])) {
        return has_completed_category_criteria($userid, $details['criteria']['category_ids']);
      }
    }
  }

  // Default to true if no criteria found for the stage
  return true;
}

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
function has_completed_category_criteria($userid, $sub_category_ids)
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
