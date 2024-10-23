<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();

// Set the page context to avoid $PAGE->context errors
$PAGE->set_context($context);

// Check if the user has either 'moodle/site:config' or 'moodle/site:viewparticipants' capability
if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  // Redirect to the homepage if the user lacks both capabilities
  redirect(new moodle_url('/'));
  exit;
}

$PAGE->set_url(new moodle_url('/local/proposed_group_requests/index.php'));
$PAGE->set_title('Proposed New Group Requests');
$PAGE->set_heading('Proposed New Group Requests');
$PAGE->set_pagelayout('standard');

$PAGE->requires->css('/local/proposed_groups/proposed_group_requests.css');

echo $OUTPUT->header();
?>
<h1 class="mb-4">Proposed Group Requests</h1>
<table id="proposedRequestsTable" class="table table-bordered dataTable">
  <thead>
    <tr>
      <th>Request By</th>
      <th>Group Name</th>
      <th>Description</th>
      <th>Target Audience</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
</table>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const proposedRequestsTable = new DataTable('#proposedRequestsTable', {
      ajax: 'api/fetch_requests.php',
      order: [],
      columns: [{
          data: 'user',
          render: function(data, type, row) {
            const userProfileUrl = `<?php echo $CFG->wwwroot; ?>/user/profile.php?id=${row.userid}`;
            return `<a href="${userProfileUrl}" target="_blank" class="text-blue">${data}</a>`;
          }
        },
        {
          data: 'groupname'
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
            const approveBtn = `<button onclick="confirmApprove(${data})" class="btn btn-success btn-sm mr-0" style="min-width:70px;">Approve</button>`;
            const rejectBtn = `<button onclick="confirmReject(${data})" class="btn btn-warning btn-sm" style="min-width:70px;">Reject</button>`;
            const deleteBtn = `<button onclick="confirmDelete(${data})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>`;

            return `<div class="d-flex gap-2 flex-wrap justify-content-center">${row.status == 0 ? `${approveBtn} ${rejectBtn}` : deleteBtn}</div>`
          }
        }
      ]
    });

    window.confirmApprove = function(requestId) {
      if (confirm('Are you sure you want to approve this request?')) {
        processRequest('approve', requestId);
      }
    };

    window.confirmReject = function(requestId) {
      if (confirm('Are you sure you want to reject this request?')) {
        processRequest('reject', requestId);
      }
    };

    window.confirmDelete = function(requestId) {
      if (confirm('Are you sure you want to delete this request?')) {
        processRequest('delete', requestId);
      }
    };

    function processRequest(action, requestId) {
      const endpoint = `api/${action}_request.php`;
      fetch(endpoint, {
          method: action === 'delete' ? 'DELETE' : 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            requestid: requestId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            proposedRequestsTable.ajax.reload(null, false); // Reload data without resetting pagination
          } else {
            alert(`Failed to ${action} request: ${data.error}`);
          }
        })
        .catch(error => console.error('Error:', error));
    }
  });
</script>

<?php
echo $OUTPUT->footer();
