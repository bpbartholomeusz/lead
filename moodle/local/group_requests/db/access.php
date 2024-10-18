<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
  'local/group_requests:view' => [
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW,
      'admin' => CAP_ALLOW,
    ],
  ],
];
