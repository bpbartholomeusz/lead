<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Check if the user has site configuration permissions

  $ADMIN->add('localplugins', new admin_category('group_requests_category', get_string('pluginname', 'local_group_requests')));

  // Add a new settings page within the category
  $settingspage = new admin_externalpage(
    'group_requests', // Unique name for the page
    get_string('pluginname', 'local_group_requests'), // Display name
    new moodle_url('/local/group_requests'), // URL of the page
    'local/group_requests:view' // Capability requirement
  );

  // Add the settings page under the custom category in the 'localplugins' section
  $ADMIN->add('group_requests_category', $settingspage);
}
