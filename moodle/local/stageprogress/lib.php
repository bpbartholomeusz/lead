<?php
defined('MOODLE_INTERNAL') || die();

function local_stageprogress_extend_navigation_course($navigation, $course, $context)
{
  global $CFG, $PAGE;

  if (has_capability('local/stageprogress:view', $context)) {
    $url = new moodle_url('/local/stageprogress');
    $navigation->add(
      get_string('stageprogress', 'local_stageprogress'),
      $url,
      navigation_node::NODETYPE_LEAF,
      null,
      'stageprogress',
      new pix_icon('i/report', '')
    );
  }
}
