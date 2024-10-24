<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
  'local/send_email:view' => [
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW,
    ],
  ],
  'local/send_email:send' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => array(
      'manager' => CAP_ALLOW,
    ),
  ),
);
