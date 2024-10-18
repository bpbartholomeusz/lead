<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
  $ADMIN->add('localplugins', new admin_externalpage(
    'local_impact_network',
    get_string('pluginname', 'local_impact_network'),
    new moodle_url('/local/impact_network')
  ));
}
