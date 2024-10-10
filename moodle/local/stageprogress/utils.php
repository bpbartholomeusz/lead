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
