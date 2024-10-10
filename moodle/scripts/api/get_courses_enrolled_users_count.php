<?php
require_once(__DIR__ . '/../../config.php');

// Check if the user is logged in
require_login();

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method. Use POST.']);
    exit;
}

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Get the 'ids' parameter (list of course IDs) from POST body
    $course_ids = isset($_POST['ids']) ? $_POST['ids'] : '';

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

        // Use Moodle's built-in function to count all enrolled users
        $users_count = count_enrolled_users($context);

        // Add the course ID and user count to results
        $results[] = [
            'courseid' => $courseid,
            'count' => $users_count,
        ];
    }

    // Return the results as JSON
    echo json_encode(['data' => $results]);

} catch (Exception $e) {
    // Catch and return any errors that occur
    echo json_encode(['error' => $e->getMessage()]);
}
