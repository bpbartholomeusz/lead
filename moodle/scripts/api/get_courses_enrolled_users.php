<?php
require_once(__DIR__ . '/../../config.php');

// Check if the user is logged in
require_login();

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Get the 'ids' parameter (list of course IDs)
    $course_ids = optional_param('ids', '', PARAM_SEQUENCE);

    // If no course IDs are provided, return an empty data array
    if (empty($course_ids)) {
        echo json_encode(['data' => []]);
        exit;
    }

    // Explode course_ids into an array
    $course_ids_array = explode(',', $course_ids);

    // Prepare an array to hold the results
    $results = [];

    foreach ($course_ids_array as $courseid) {
        // Ensure the course ID is valid
        if (!is_numeric($courseid)) {
            throw new Exception("Invalid course ID: $courseid");
        }

        // Fetch the course context
        $context = context_course::instance($courseid, IGNORE_MISSING);

        if (!$context) {
            throw new Exception("Course ID $courseid not found.");
        }

        // Get all enrolled users for the course
        $enrolled_users = get_enrolled_users($context);

        // Get user count
        $users_count = count($enrolled_users);

        // Prepare a list of users with their roles
        $users_list = [];
        foreach ($enrolled_users as $user) {
            // Fetch the roles of the user in this course context
            $roles = get_user_roles($context, $user->id, true);

            // Prepare a list of role names
            $role_names = [];
            foreach ($roles as $role) {
                $role_names[] = role_get_name($role, $context);
            }

            // Add the user's details including roles
            $users_list[] = [
                'id' => $user->id,
                'fullname' => fullname($user),
                'email' => $user->email,
                'roles' => $role_names
            ];
        }

        // Add the course ID, user count, and list of users with roles to results
        $results[] = [
            'courseid' => $courseid,
            'count' => $users_count,
            'users' => $users_list
        ];
    }

    // Return the results as JSON
    echo json_encode(['data' => $results]);

} catch (Exception $e) {
    // Catch and return any errors that occur
    echo json_encode(['error' => $e->getMessage()]);
}
