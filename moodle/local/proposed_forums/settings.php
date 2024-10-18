<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
  $ADMIN->add('localplugins', new admin_category('proposed_forums_category', get_string('pluginname', 'local_proposed_forums')));
  $settingspage = new admin_externalpage(
    'proposed_forums',
    get_string('pluginname', 'local_proposed_forums'),
    new moodle_url('/local/proposed_forums/index.php'),
    'moodle/site:config'
  );
  $ADMIN->add('proposed_forums_category', $settingspage);
}
