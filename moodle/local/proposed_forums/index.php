<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/proposed_forums/index.php'));
$PAGE->set_title('Proposed Forums');
$PAGE->set_heading('Proposed Forums');
$PAGE->set_pagelayout('standard');
$PAGE->requires->css('/local/proposed_forums/proposed_forums.css');

echo $OUTPUT->header();
?>
<h1 class="mb-4">Proposed Forum Requests</h1>
<table id="proposedForumsTable" class="table table-bordered dataTable">
  <thead>
    <tr>
      <th>Request By</th>
      <th>Forum Name</th>
      <th>Description</th>
      <th>Target Audience</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
</table>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = new DataTable('#proposedForumsTable', {
      ajax: 'api/fetch_requests.php',
      columns: [{
          data: 'user',
          render: function(data, type, row) {
            const userProfileUrl = `<?php echo $CFG->wwwroot; ?>/user/profile.php?id=${row.userid}`;
            return `<a href="${userProfileUrl}" target="_blank" class="text-blue">${data}</a>`;
          }
        },
        {
          data: 'forumname',
          render: function(data, type, row) {
            if (row.status == 1) { // If status is "Approved"
              const forumUrl = `<?php echo $CFG->wwwroot; ?>/mod/forum`;
              return `<a href="${forumUrl}" target="_blank" class="text-blue">${data}</a>`;
            }
            return data; // Just the text for Pending or Rejected
          }
        },
        {
          data: 'description'
        },
        {
          data: 'audience'
        },
        {
          width: '10%',
          data: 'status',
          render: function(data) {
            return data == 1 ? 'Approved' : (data == 2 ? 'Rejected' : 'Pending');
          }
        },
        {
          width: '10%',
          data: 'id',
          render: function(data, type, row) {
            const approveBtn = `<button onclick="approveRequest(${data})" class="btn btn-success btn-sm mr-0" style="min-width:70px;">Approve</button>`;
            const rejectBtn = `<button onclick="rejectRequest(${data})" class="btn btn-danger btn-sm" style="min-width:70px;">Reject</button>`;
            const deleteBtn = `<button onclick="deleteRequest(${data})" class="btn btn-warning btn-sm" style="min-width:70px;">Delete</button>`;

            return `<div class="d-flex gap-2 flex-wrap justify-content-center">${row.status == 0 ? `${approveBtn} ${rejectBtn}` : deleteBtn}</div>`
          }
        }
      ]
    });

    window.approveRequest = function(requestId) {
      if (confirm('Are you sure you want to approve this request?')) {
        fetch('api/approve_request.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              requestid: requestId
            })
          })
          .then(response => response.json())
          .then(data => data.success ? table.ajax.reload() : alert('Error approving request.'));
      }
    };

    window.rejectRequest = function(requestId) {
      if (confirm('Are you sure you want to reject this request?')) {
        fetch('api/reject_request.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              requestid: requestId
            })
          })
          .then(response => response.json())
          .then(data => data.success ? table.ajax.reload() : alert('Error rejecting request.'));
      }
    };

    window.deleteRequest = function(requestId) {
      if (confirm('Are you sure you want to delete this request?')) {
        fetch('api/delete_request.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              requestid: requestId
            })
          })
          .then(response => response.json())
          .then(data => data.success ? table.ajax.reload() : alert('Error deleting request.'));
      }
    };
  });
</script>

<?php
echo $OUTPUT->footer();
