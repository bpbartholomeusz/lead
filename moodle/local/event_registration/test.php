<?php
require_once('../../config.php'); // Include Moodle config file.
require_login(); // Ensure the user is logged in.

global $DB;

// Get site-level groups.
$sitecontext = context_system::instance();
$sitegroups = $DB->get_records_sql(
    "SELECT g.id, g.name
     FROM {groups} g
     WHERE g.courseid = 1" // courseid = 1 represents the site-level groups.
);

// Prepare site-level group options for the dropdown.
$sitegroupoptions = [];
foreach ($sitegroups as $group) {
    $sitegroupoptions[$group->id] = $group->name;
}

print_r($sitegroupoptions);