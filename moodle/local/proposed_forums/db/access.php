<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
  'local/proposed_forums:view' => [
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW
    ]
  ],
  'local/proposed_forums:approve' => [
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW
    ]
  ]
];
