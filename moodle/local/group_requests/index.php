<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/group_requests/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('group_requests', 'local_group_requests'));
$PAGE->set_heading(get_string('request_list', 'local_group_requests'));
$PAGE->requires->css('/local/group_requests/group_requests.css');

echo $OUTPUT->header();

global $DB;

// Fetch group requests with user data and group names, handling missing groups with LEFT JOIN
$sql = "
    SELECT gr.id, gr.status, u.id AS userid, u.firstname, u.lastname, g.name AS groupname
    FROM {group_requests} gr
    JOIN {user} u ON u.id = gr.userid
    LEFT JOIN {groups} g ON g.id = gr.groupid
    WHERE gr.status = 0
";
$requests = $DB->get_records_sql($sql);
?>
<table class="table table-bordered" id="requestsTable">
  <thead>
    <tr>
      <th>User</th>
      <th>Group</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($requests)): ?>
      <?php foreach ($requests as $request): ?>
        <tr id="request-<?php echo $request->id; ?>">
          <td>
            <?php
            $userurl = new moodle_url('/user/profile.php', ['id' => $request->userid]);
            $fullname = fullname($request);
            ?>
            <a href="<?php echo $userurl; ?>" target="_blank" class="text-blue"><?php echo $fullname; ?></a>
          </td>
          <td><?php echo $request->groupname ? $request->groupname : 'Non-existing group'; ?></td>
          <td><?php echo $request->status ? 'Approved' : 'Pending'; ?></td>
          <td>
            <button onclick="approveRequest(<?php echo $request->id; ?>)" class="btn btn-success btn-sm">Approve</button>
            <button onclick="deleteRequest(<?php echo $request->id; ?>)" class="btn btn-danger btn-sm">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#requestsTable');
  });

  function deleteRequest(requestId) {
    if (confirm('Are you sure you want to delete this request?')) {
      fetch('api/delete_request.php', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            requestid: requestId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const row = document.getElementById('request-' + requestId);
            row.parentNode.removeChild(row);
          } else {
            alert('Failed to delete request: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  }

  function approveRequest(requestId) {
    if (confirm('Are you sure you want to approve this request?')) {
      fetch('api/approve_request.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            requestid: requestId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update the status cell to show 'Approved'
            const row = document.getElementById('request-' + requestId);
            row.cells[2].innerHTML = 'Approved';
            row.cells[4].innerHTML = ''
          } else {
            alert('Failed to approve request: ' + data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  }
</script>

<?php
echo $OUTPUT->footer();
