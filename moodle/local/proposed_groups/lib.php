<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Extends the admin navigation with a "Proposed Groups" link.
 *
 * @param global_navigation $nav The global navigation object.
 */
function local_proposed_groups_extend_navigation(global_navigation $nav)
{
  global $PAGE, $CFG;

  // Only add for site admins.
  if (is_siteadmin()) {
    // Ensure we are modifying the admin area navigation.
    $adminnode = $nav->find('siteadmin', global_navigation::TYPE_SITE_ADMIN);

    if ($adminnode) {
      // Add the Proposed Groups link under site administration in the admin tab.
      $adminnode->add(
        get_string('pluginname', 'local_proposed_groups'),
        new moodle_url('/local/proposed_groups/index.php'),
        navigation_node::TYPE_CUSTOM,
        'proposedgroups',
        null,
        new pix_icon('i/settings', '')
      );
    }
  }
}
