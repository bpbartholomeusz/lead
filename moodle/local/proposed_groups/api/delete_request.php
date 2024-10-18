<?php
// delete_request.php

require_once('../../../config.php');

require_login(); // Ensure the user is logged in
$context = context_system::instance();

// Check that the user has the necessary capabilities (admin only)
if (!is_siteadmin()) {
  http_response_code(403); // Forbidden
  echo json_encode(['error' => 'Access denied: Administrator access required']);
  exit;
}

header('Content-Type: application/json'); // Set response type

try {
  // Only accept DELETE requests
  if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    throw new Exception('Only DELETE requests are allowed', 405);
  }

  // Get the request data from DELETE input
  $data = json_decode(file_get_contents('php://input'), true);
  $requestId = $data['requestid'] ?? null;

  if (empty($requestId)) {
    throw new Exception('Invalid request ID', 400);
  }

  global $DB;

  // Check if the request exists before attempting deletion
  if (!$DB->record_exists('proposed_groups_requests', ['id' => $requestId])) {
    throw new Exception('Request does not exist', 404);
  }

  // Delete the proposed group request
  $DB->delete_records('proposed_groups_requests', ['id' => $requestId]);

  // Respond with success message
  echo json_encode(['success' => true, 'message' => 'Request deleted successfully']);
} catch (Exception $e) {
  http_response_code($e->getCode() ?: 500); // Internal Server Error
  echo json_encode([
    'error' => $e->getMessage(),
    'details' => $e->getTraceAsString()
  ]);
}
