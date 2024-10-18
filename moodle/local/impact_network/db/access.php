<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
  'local/impact_network:view' => [
    'riskbitmask' => RISK_CONFIG,

    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'manager' => CAP_ALLOW,
      'admin' => CAP_ALLOW
    ]
  ],
  'local/impact_network:manageevents' => [
    'riskbitmask' => RISK_CONFIG | RISK_XSS,

    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => [
      'admin' => CAP_ALLOW
    ]
  ],
];
