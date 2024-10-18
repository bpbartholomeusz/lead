<?php
require_once(__DIR__ . '/../../config.php');

// Check if the user is logged in
require_login();

// Set the content type to JSON
header('Content-Type: application/json');

global $USER;

// Check if the user is a guest
if (isguestuser()) {
  echo json_encode(["userid" => null]);
  exit;
}




// Moodle API URL
$api_url = "https://leaddev.leadcurriculum.cloud/webservice/rest/server.php";

// Token generated from Moodle
$token = "1f107aaa6b10480f4c90ae41396cef70";

// The ID of the existing calendar event
$existing_event_id = 488;  // Replace with the actual event ID

// The ID of the user you want to add to the event
$user_id = $USER->id;  // Replace with the actual user ID



// Moodle API URL and token.
$domainname = 'https://leaddev.leadcurriculum.cloud';  // Replace with your Moodle domain
$functionname = 'core_calendar_get_calendar_events';




$serverurl = $domainname . '/webservice/rest/server.php?wstoken=' . $token . '&wsfunction=' . $functionname . '&moodlewsrestformat=json';

// Prepare the event data
$event_data = [
    'events' => [
        [
            'name' => 'Course Event', // Name of the event
            'description' => 'This event is related to the course.', // Description
            'timestart' => strtotime('2024-01-01 10:00:00'), // Start time (UNIX timestamp)
            'timeend' => strtotime('2024-01-01 12:00:00'), // End time (UNIX timestamp)
            'visible' => 1, // Set to 1 to make it visible
            'courseid' => 120, // ID of the course to which the event belongs
            'userid' => [3], // Array of user IDs of participants (users to be added to the event)
            'eventtype' => 'course', // Event type: 'course' for course-specific events
        ],
    ]
];

// Use curl to send the request.
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $serverurl);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($event_data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request.
$response = curl_exec($curl);
curl_close($curl);

// Decode the JSON response.
$result = json_decode($response, true);

// Check the result
if (isset($result['events'])) {
    echo "Event created successfully.\n";
} else {
    echo "Error: test" . print_r($result, true);
}

echo "test";
exit();

// Web service URL format.
$serverurl = $domainname . '/webservice/rest/server.php?wstoken=' . $token . '&wsfunction=' . $functionname . '&moodlewsrestformat=json';

// Parameters for the core_calendar_get_calendar_events function.
$event_params = [
    'events' => [], // Leave empty for all events
    'options' => [
        'userevents' => 1,
        'siteevents' => 1,
        'timestart' => strtotime('2024-01-01'), // Start date
        'timeend' => strtotime('2024-12-31') // End date
    ]
];

// Use curl to send the request.
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $serverurl);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($event_params));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
curl_close($curl);

// Decode the JSON response.
$events = json_decode($response, true);
print_r($events);
// Check and display the results.
if (isset($events['events'])) {
    foreach ($events['events'] as $event) {
        echo "ID: " . $event['id'] . "\n";
        echo "Event: " . $event['name'] . "\n";
        echo "Start Time: " . date('Y-m-d H:i:s', $event['timestart']) . "\n";
        echo "Description: " . $event['description'] . "\n\n";
    }

    exit();
    $event_id_to_find = '2';
    $event_details = null;
    
        // Loop through the events to find the one with the matching event_id
        if (isset($events['events']) && !empty($events['events'])) {
            foreach ($events['events'] as $event) {
                if ($event['id'] == $event_id_to_find) {
                    $event_details = $event;
                    break;
                }
            }
        }
        
        // Output the found event or a message if not found
        if ($event_details) {
            echo "Event found:\n";
            print_r($event_details);


            $event = $event_details['events'][0];

            // Step 2: Create a new event for the user using core_calendar_create_calendar_events
            $ws_function_create = "core_calendar_create_calendar_events";
        
            $data_create = array(
                'wstoken' => $token,
                'wsfunction' => $ws_function_create,
                'moodlewsrestformat' => 'json',
                'events[0][name]' => $event['name'],  // Event name
                'events[0][description]' => $event['description'],  // Event description
                'events[0][format]' => $event['format'],  // Format (HTML = 1)
                'events[0][timestart]' => $event['timestart'],  // Start time (Unix timestamp)
                'events[0][timeduration]' => $event['timeduration'],  // Duration
                'events[0][eventtype]' => 'user',  // Set event type to 'user'
                'events[0][userid]' => $user_id,  // Set the user ID
            );
        
            // Initialize cURL session to create the event for the user
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_create);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
            // Execute the cURL request to create the event
            $response_create = curl_exec($curl);
            curl_close($curl);
        
            // Decode the JSON response
            $response_data = json_decode($response_create, true);
        
            // Check if the event was successfully created
            if (isset($response_data['exception'])) {
                echo "Error: " . $response_data['message'];
            } else {
                echo "User successfully registered to the event!";
            }
        


        } else {
            echo "Event with ID $event_id_to_find not found.";
        }


} else {
    echo "Error: " . print_r($events, true);
}

exit();


if (isset($event_details['events'][0])) {
    // Fetch the existing event details
    $event = $event_details['events'][0];

    // Step 2: Create a new event for the user using core_calendar_create_calendar_events
    $ws_function_create = "core_calendar_create_calendar_events";

    $data_create = array(
        'wstoken' => $token,
        'wsfunction' => $ws_function_create,
        'moodlewsrestformat' => 'json',
        'events[0][name]' => $event['name'],  // Event name
        'events[0][description]' => $event['description'],  // Event description
        'events[0][format]' => $event['format'],  // Format (HTML = 1)
        'events[0][timestart]' => $event['timestart'],  // Start time (Unix timestamp)
        'events[0][timeduration]' => $event['timeduration'],  // Duration
        'events[0][eventtype]' => 'user',  // Set event type to 'user'
        'events[0][userid]' => $user_id,  // Set the user ID
    );

    // Initialize cURL session to create the event for the user
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_create);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request to create the event
    $response_create = curl_exec($curl);
    curl_close($curl);

    // Decode the JSON response
    $response_data = json_decode($response_create, true);

    // Check if the event was successfully created
    if (isset($response_data['exception'])) {
        echo "Error: " . $response_data['message'];
    } else {
        echo "User successfully registered to the event!";
    }

} else {
    echo "Event not found!";
}

?>



