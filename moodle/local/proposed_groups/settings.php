<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Check if the user has site configuration permissions (typically admins only).

  // Create a new admin category under "Local plugins" for Proposed Groups.
  $category = new admin_category('proposed_groups_category', get_string('pluginname', 'local_proposed_groups'));

  // Add the new category to the "localplugins" section in the admin menu.
  $ADMIN->add('localplugins', $category);

  // Define a new settings page for Proposed Groups.
  $settingspage = new admin_externalpage(
    'proposed_groups', // The unique identifier for this page.
    get_string('pluginname', 'local_proposed_groups'), // The display name of the page.
    new moodle_url('/local/proposed_groups/index.php'), // URL to the page.
    'moodle/site:config' // Capability required to view this page (admin only by default).
  );

  // Add the settings page under the Proposed Groups category in the admin menu.
  $ADMIN->add('proposed_groups_category', $settingspage);
}
