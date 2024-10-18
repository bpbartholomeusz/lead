<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
  'local/proposed_groups:view' => [
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW,
      'admin' => CAP_ALLOW,
    ],
  ],
];
