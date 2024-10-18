<?php
require_once(__DIR__ . '/../../config.php');

// Check if the user is logged in
require_login();

// Set the content type to JSON
header('Content-Type: application/json');

global $USER;

// Check if the user is a guest
if (isguestuser()) {
  echo json_encode(["userid" => null]);
  exit;
}

// Output the current user's ID
echo json_encode(["userid" => (int)$USER->id]);
