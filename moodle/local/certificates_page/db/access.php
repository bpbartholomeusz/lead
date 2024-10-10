<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
  'local/certificates_page:view' => array(
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => array(
      'manager' => CAP_ALLOW,  // Only managers (admins) can view the page.
    ),
  ),
);
