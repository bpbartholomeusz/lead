<?php
// delete_request.php

require_once('../../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  http_response_code(403); // Set the HTTP response code to 403 (Forbidden)
  echo json_encode(['error' => 'Access denied']); // Return an access denied message
  exit;
}

header('Content-Type: application/json');

try {
  // Only accept DELETE requests
  if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    throw new Exception('Only DELETE requests are allowed', 405);
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = $data['requestid'] ?? null;

  if (empty($requestId)) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;

  if (!$DB->record_exists('proposed_forums_requests', ['id' => $requestId])) {
    throw new Exception('Request does not exist', 404);
  }

  $DB->delete_records('proposed_forums_requests', ['id' => $requestId]);

  echo json_encode(['success' => true, 'message' => 'Request deleted successfully']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500);
  echo json_encode(['error' => $e->getMessage()]);
}
