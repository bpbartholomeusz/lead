<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
  'local/stageprogress:view' => array(
    'captype' => 'read',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
      'student' => CAP_ALLOW,
      'teacher' => CAP_ALLOW,
      'manager' => CAP_ALLOW,
      'admin' => CAP_ALLOW
    ),
  ),
);
