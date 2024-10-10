<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'leaddev-rds.cv60ucs2an0k.eu-west-2.rds.amazonaws.com';
//$CFG->dbhost    = 'moodle-lead.cv60ucs2an0k.eu-west-2.rds.amazonaws.com';
//$CFG->dbhost    = 'moodle-backup.cv60ucs2an0k.eu-west-2.rds.amazonaws.com';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'moodleuser';
$CFG->dbpass    = 'moodleuser';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_0900_ai_ci',
);

$CFG->wwwroot   = 'https://leaddev.leadcurriculum.cloud';
//$CFG->wwwroot   = 'https://moodle.leadcurriculum.cloud';
//$CFG->wwwroot   = 'http://35.178.207.215';
$CFG->dataroot  = '/var/www/html/moodledata';
$CFG->admin     = 'admin';
$CFG->sslproxy = true;
$CFG->directorypermissions = 0777;

//$CFG->alternative_file_system_class = '\\tool_objectfs\\s3_file_system';
//$CFG->tool_objectfs_s3_key = 'AKIA47CRWVFCB6GGBW4I';
//$CFG->tool_objectfs_s3_secret = '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';
//$CFG->tool_objectfs_s3_bucket = 'leadmoodle-site-storage';
//$CFG->tool_objectfs_s3_region = 'eu-west-2';

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
