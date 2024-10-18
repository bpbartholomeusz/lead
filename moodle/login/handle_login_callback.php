<?php
// handle_login_callback.php

defined('MOODLE_INTERNAL') || die(); // Prevent direct access to this file
function joinGroup($gid) {
    global $USER;
    // Fetch the current user ID
    $userid = $USER->id;



    // Define the token and API URL
    $token = '4403d4e0966f92de1b275114ab20274e'; // Replace with your actual token
    $url = 'https://moodle.leadcurriculum.cloud/webservice/rest/server.php';

    // Prepare the API request to add the user to the group
    $body = http_build_query([
        'wstoken' => $token,
        'wsfunction' => 'core_group_add_group_members',
        'moodlewsrestformat' => 'json',
        'members[0][groupid]' => (int)$gid,
        'members[0][userid]' => (int)$userid,
    ]);

    // Set up the HTTP context for the POST request
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $body,
        ]
    ];

    // Execute the API request
    $response = file_get_contents($url, false, stream_context_create($options));
    $data = json_decode($response, true);

    redirect(new moodle_url('/message/index.php'));
}
