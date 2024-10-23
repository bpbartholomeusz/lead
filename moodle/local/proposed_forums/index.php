<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();

if (!has_capability('moodle/site:config', $context) && !has_capability('moodle/site:viewparticipants', $context)) {
  redirect(new moodle_url('/'));
  exit;
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/proposed_forums/index.php'));
$PAGE->set_title('Proposed Forums');
$PAGE->set_heading('Proposed Forums');
$PAGE->set_pagelayout('standard');
$PAGE->requires->css('/local/proposed_forums/proposed_forums.css');

$PAGE->requires->js(new moodle_url('https://cdn.tiny.cloud/1/f9h8tk91bs7njcugeukqacydyf1adlyboxeo3ucxnn2y0d3s/tinymce/7/tinymce.min.js'), true);


echo $OUTPUT->header();
?>
<div class="proposed-forums-wrapper">
  <h1 class="mb-4">Manage Forums</h1>

  <!-- Add Forum Button -->
  <button id="addForumButton" class="btn btn-primary mb-3">Add Forum</button>

  <!-- Forums Table -->
  <table id="forumsTable" class="table table-bordered dataTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>

  <hr />
  <h1 class="mt-5 mb-4">Forum Requests</h1>

  <!-- Forum Requests Table -->
  <table id="forumRequestsTable" class="table table-bordered dataTable">
    <thead>
      <tr>
        <th>User Full Name</th>
        <th>Forum Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
  </table>

  <!-- Add/Update Forum Modal -->
  <div id="forumModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add New Forum</h5>
          <button type="button" class="close" onclick="closeModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="forumForm">
            <input type="hidden" id="forumid" name="forumid">
            <div class="form-group">
              <label for="forumname">Forum Name</label>
              <input type="text" class="form-control" id="forumname" name="forumname" required>
            </div>
            <div class="form-group">
              <label for="forumdescription">Forum Description</label>
              <textarea class="form-control" id="forumdescription" name="forumdescription" rows="5"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
          <button type="button" id="saveForumButton" class="btn btn-primary">Save Forum</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE
    tinymce.init({
      selector: 'textarea#forumdescription',
      plugins: [
        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
      ],
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      height: 300,
      menubar: false, // Optional: To show or hide the menubar
      branding: false, // Optional: To hide the "Powered by TinyMCE" branding
    });



    // DataTable for Forums
    const forumsTable = new DataTable('#forumsTable', {
      ajax: 'api/fetch_forums.php',
      order: [],
      columns: [{
          data: 'forumname',
          render: function(data, type, row) {
            $forumUrl = `<?php echo $CFG->wwwroot; ?>/mod/forum/view.php?f=${row.id}`;
            return `<a href="${$forumUrl}" target="_blank" class="text-blue">${data}</a>`;
          }
        },
        {
          data: 'description'
        },
        {
          width: '10%',
          data: 'id',
          render: function(data) {
            const editBtn = `<button onclick="showEditForumModal(${data})" class="btn btn-primary btn-sm mr-0"><i class="fa fa-edit"></i></button>`;
            const deleteBtn = `<button onclick="deleteForum(${data})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>`;
            return `<div class="d-flex gap-2 flex-wrap">${editBtn} ${deleteBtn}</div>`;
          }
        }
      ]
    });

    // Show Edit Modal and populate fields
    window.showEditForumModal = function(forumId) {
      // Get the data for the row based on the forum ID
      const rowData = forumsTable.rows().data().toArray().find(row => row.id == forumId);

      if (!rowData) {
        alert('Forum not found!');
        return;
      }

      const forumName = rowData.forumname;

      // Create a temporary element to parse the row description (which contains HTML)
      const tempElement = document.createElement('div');
      tempElement.innerHTML = rowData.description; // Set the HTML from the table

      // Extract the content inside the .text_to_html div
      const forumDescription = tempElement.querySelector('.text_to_html').innerHTML;

      // Populate the modal fields with the retrieved data
      document.getElementById('forumid').value = forumId;
      document.getElementById('forumname').value = forumName;
      // document.getElementById('forumdescription').value = forumDescription; // Set HTML content here
      // Use TinyMCE API to set the content in the editor
      if (tinymce.get('forumdescription')) {
        tinymce.get('forumdescription').setContent(forumDescription); // Set content in the TinyMCE editor
      }

      document.getElementById('forumModal').style.display = 'block'; // Show the modal
    };

    // DataTable for Forum Requests
    const forumRequestsTable = new DataTable('#forumRequestsTable', {
      ajax: 'api/fetch_forum_requests.php',
      order: [],
      columns: [{
          data: 'user'
        },
        {
          data: 'forumname'
        },
        {
          data: 'status',
          render: function(data) {
            return data == 1 ? 'Approved' : (data == 2 ? 'Rejected' : 'Pending');
          }
        },
        {
          width: '10%',
          data: 'id',
          render: function(data, type, row) {
            const approveBtn = `<button onclick="updateRequest(${data}, 1)" class="btn btn-success btn-sm mr-0">Approve</button>`;
            const rejectBtn = `<button onclick="updateRequest(${data}, 2)" class="btn btn-warning btn-sm">Reject</button>`;
            const deleteBtn = `<button onclick="deleteRequest(${data})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>`;

            return row.status == 0 ? `<div class="d-flex justify-content-center gap-2">${approveBtn} ${rejectBtn}</div>` : deleteBtn;
          }
        }
      ]
    });

    // Open Add Forum Modal
    document.getElementById('addForumButton').addEventListener('click', () => openModal('Add New Forum'));

    // Save Forum (Add or Update)
    document.getElementById('saveForumButton').addEventListener('click', () => {
      const forumid = document.getElementById('forumid').value;
      const url = forumid ? 'api/update_forum.php' : 'api/add_forum.php';
      submitForum(url);
    });

    // Delete Forum
    window.deleteForum = function(forumId) {
      if (confirm('Are you sure you want to delete this forum?')) {
        fetch('api/delete_forum.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              forumid: forumId
            })
          })
          .then(response => response.json())
          .then(data => data.success ? forumsTable.ajax.reload() : alert('Error deleting forum.'));
      }
    };

    // Update Forum Request
    window.updateRequest = function(requestId, status) {
      if (confirm(`Are you sure you want to ${status == 1 ? 'approve' : 'reject'} this request?`)) {
        const url = status === 1 ? 'api/approve_request.php' : 'api/reject_request.php';
        fetch(url, {
            method: 'POST',
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
              forumRequestsTable.ajax.reload();
              if (status == 1) {
                forumsTable.ajax.reload();
              }
            } else {
              alert('Error updating request: ' + (data.error || 'Unknown error'));
            }
          });
      }
    };

    // Open Modal for Adding/Editing Forum
    window.openModal = function(title, forum = null) {
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('forumid').value = forum ? forum.id : '';
      document.getElementById('forumname').value = forum ? forum.forumname : '';
      document.getElementById('forumdescription').value = forum ? forum.forumdescription : '';
      document.getElementById('forumModal').style.display = 'block';
    }

    // Close Modal and Reset Form
    window.closeModal = function() {
      document.getElementById('forumModal').style.display = 'none';
      document.getElementById('forumForm').reset();
    }

    // Submit Forum (Add or Update)
    window.submitForum = function(url) {
      // Ensure the TinyMCE content is stored back in the textarea
      tinymce.triggerSave();

      const forumid = document.getElementById('forumid').value.trim();
      const forumname = document.getElementById('forumname').value.trim();
      const forumdescription = document.getElementById('forumdescription').value.trim();

      fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            forumid,
            forumname,
            forumdescription
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeModal();
            forumsTable.ajax.reload();
          } else {
            alert('Error saving forum: ' + (data.error || 'Unknown error'));
          }
        });
    }

    // Delete Request
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
          .then(data => data.success ? forumRequestsTable.ajax.reload() : alert('Error deleting request.'));
      }
    }
  });
</script>

<?php
echo $OUTPUT->footer();
?>
