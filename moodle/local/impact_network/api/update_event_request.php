<?php
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

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);
$requestid = isset($input['requestid']) ? (int)$input['requestid'] : null;
$status = isset($input['status']) ? (int)$input['status'] : null;

// Validate input, excluding "Pending" status (0)
if (is_null($requestid) || !in_array($status, [1, 2])) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Invalid request. Only approved or rejected status are allowed.']);
  exit;
}

try {
  global $DB;

  // Check if the join request exists
  $request = $DB->get_record('impact_event_requests', ['id' => $requestid], '*', MUST_EXIST);

  // Fetch user and event information
  $user = $DB->get_record('user', ['id' => $request->userid], '*', MUST_EXIST);
  $event = $DB->get_record('impact_events', ['id' => $request->eventid], 'id, eventname', MUST_EXIST);

  // If approved, add the user to impact_event_members
  if ($status === 1) { // 1 = Approved
    // Check if user is already a member of the event
    $is_member = $DB->record_exists('impact_event_members', [
      'eventid' => $event->id,
      'userid' => $user->id
    ]);

    if (!$is_member) {
      $DB->insert_record('impact_event_members', (object)[
        'eventid' => $event->id,
        'userid' => $user->id,
        'timecreated' => time()
      ]);
    }
  }

  // Prepare and send notification
  $message = new \core\message\message();
  $message->component = 'moodle';
  $message->name = 'instantmessage';
  $message->userfrom = \core_user::get_noreply_user();
  $message->userto = $user;
  $message->subject = "Your request to join the event '{$event->eventname}' has been " . ($status == 1 ? 'approved' : 'rejected');
  $message->fullmessage = "Hello {$user->firstname},\n\nYour request to join the event '{$event->eventname}' has been " . ($status == 1 ? 'approved. You are now a member of the event.' : 'rejected. Unfortunately, you will not be able to join the event.') . "\n\nThank you!";
  $message->fullmessageformat = FORMAT_PLAIN;
  $message->fullmessagehtml = "<p>Hello {$user->firstname},</p><p>Your request to join the event <strong>{$event->eventname}</strong> has been " . ($status === 1 ? '<strong>approved</strong>. You are now a member of the event.' : '<strong>rejected</strong>.') . "</p>";
  $message->smallmessage = "Your request to join '{$event->eventname}' was " . ($status === 1 ? 'approved.' : 'rejected.');
  $message->notification = 1;
  $message->contexturl = new moodle_url('/local/impact_network/index.php');
  $message->contexturlname = 'Impact Network Dashboard';

  if (!message_send($message)) {
    throw new Exception('Failed to send notification.');
  }

  // Delete the join request after processing
  $DB->delete_records('impact_event_requests', ['id' => $requestid]);

  echo json_encode(['success' => true, 'message' => 'Request processed, user notified, and request deleted successfully.']);
} catch (Exception $e) {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to process the request', 'details' => $e->getMessage()]);
}
