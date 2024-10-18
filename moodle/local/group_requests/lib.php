<?php

/**
 * Deletes a group join request by its ID.
 *
 * @param int $requestid The ID of the join request to delete.
 * @return bool True on success, false on failure.
 * @throws moodle_exception if the request ID is invalid or if the deletion fails.
 */
function delete_group_request($requestid)
{
  global $DB;

  // Check if the request exists
  if (!$DB->record_exists('group_requests', ['id' => $requestid])) {
    throw new moodle_exception('Request not found');
  }

  // Attempt to delete the request
  return $DB->delete_records('group_requests', ['id' => $requestid]);
}
