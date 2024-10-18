<?php
require_once('../../config.php');
require_login();

$context = context_system::instance();
require_capability('local/impact_network:view', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/impact_network/index.php'));
$PAGE->set_title('Impact Network');
$PAGE->set_heading('Impact Network');
$PAGE->set_pagelayout('standard');
$PAGE->requires->css('/local/impact_network/impact_network.css');

echo $OUTPUT->header();
?>
<h1 class="mb-4">Impact Network Events</h1>

<!-- Add Event Button -->
<button id="addEventButton" class="btn btn-primary mb-3">Add Event</button>

<!-- Impact Network Events List Table -->
<table id="impactEventsTable" class="table table-bordered dataTable">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Description</th>
      <th>Actions</th>
    </tr>
  </thead>
</table>
<hr />
<h1 class="mt-5 mb-4">Event Requests</h1>

<!-- Event Requests Table -->
<table id="eventRequestsTable" class="table table-bordered dataTable">
  <thead>
    <tr>
      <th>User Full Name</th>
      <th>Event Name</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
</table>

<!-- Add/Update Event Modal -->
<div id="eventModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add New Event</h5>
        <button type="button" class="close" onclick="closeModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="eventForm">
          <input type="hidden" id="eventid" name="eventid">
          <div class="form-group">
            <label for="eventname">Event Name</label>
            <input type="text" class="form-control" id="eventname" name="eventname" required>
          </div>
          <div class="form-group">
            <label for="eventdescription">Event Description</label>
            <textarea class="form-control" id="eventdescription" name="eventdescription" rows="5"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        <button type="button" id="saveEventButton" class="btn btn-primary">Save Event</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const eventsTable = new DataTable('#impactEventsTable', {
      ajax: 'api/fetch_impact_network_events.php',
      order: [],
      columns: [{
          width: '10%',
          data: null,
          orderable: false,
          render: () => `<a href="javascript:void(0)" class="details-control text-blue">members</a>`
        },
        {
          data: 'eventname'
        },
        {
          data: 'eventdescription'
        },
        {
          width: '10%',
          data: 'id',
          render: function(data) {
            return `
              <div class="d-flex gap-2 flex-wrap justify-content-center">
                <button onclick="editEvent(${data})" class="btn btn-primary btn-sm">Edit</button>
                <button onclick="deleteEvent(${data})" class="btn btn-danger btn-sm">Delete</button>
              </div>
            `;
          }
        }
      ]
    });

    // Event listener for opening and closing details
    document.querySelector('#impactEventsTable').addEventListener('click', function(e) {
      if (e.target.closest('.details-control')) {
        const tr = e.target.closest('tr');
        const row = eventsTable.row(tr);

        if (row.child.isShown()) {
          row.child.hide();
          tr.classList.remove('shown');
        } else {
          const eventId = row.data().id;
          row.child('<div class="event-members">Loading members...</div>').show();
          tr.classList.add('shown');

          // Fetch and display the event members
          fetch(`api/get_event_members.php?eventid=${eventId}`)
            .then(response => response.json())
            .then(data => {
              if (data.success) {


                let membersList = data.data.map(member => `
                  <li><a href="<?php echo $CFG->wwwroot; ?>/user/profile.php?id=${member.id}" target="_blank">${member.fullname}</a></li>
                `).join('')

                row.child(data.data?.length > 0 ? `<ul>${membersList}</ul>` : '<div class="event-members text-center text-white">No members found.</div>').show();
              } else {
                row.child('<div class="event-members">Failed to load members.</div>').show();
              }
            })
            .catch(() => {
              row.child('<div class="event-members">Error loading members.</div>').show();
            });
        }
      }
    });

    // DataTable for Event Requests
    const requestsTable = new DataTable('#eventRequestsTable', {
      ajax: 'api/fetch_impact_network_join_request.php',
      order: [],
      columns: [{
          data: 'user'
        },
        {
          data: 'eventname'
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

            return row.status == 0 ? `<div class="d-flex justify-content-center gap-2">${approveBtn} ${rejectBtn}</div>` : '';
          }
        }
      ]
    });

    window.updateRequest = function(requestId, status) {
      fetch('api/update_event_request.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            requestid: requestId,
            status: status
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            requestsTable.ajax.reload();
          } else {
            alert('Error updating request: ' + (data.error || 'Unknown error'));
          }
        });
    };


    document.getElementById('addEventButton').addEventListener('click', () => openModal('Add New Event'));

    document.getElementById('saveEventButton').addEventListener('click', () => {
      const eventid = document.getElementById('eventid').value;
      const url = eventid ? 'api/update_event.php' : 'api/add_event.php';
      submitEvent(url);
    });

    window.editEvent = function(eventId) {
      fetch(`api/get_event_details.php?eventid=${eventId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            openModal('Update Event', data.event);
          } else {
            alert(data.error);
          }
        });
    };

    window.deleteEvent = function(eventId) {
      if (confirm('Are you sure you want to delete this event?')) {
        fetch('api/delete_event.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              eventid: eventId
            })
          })
          .then(response => response.json())
          .then(data => data.success ? eventsTable.ajax.reload() : alert('Error deleting event.'));
      }
    };

    function openModal(title, event = null) {
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('eventid').value = event ? event.id : '';
      document.getElementById('eventname').value = event ? event.eventname : '';
      document.getElementById('eventdescription').value = event ? event.eventdescription : '';
      document.getElementById('eventModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('eventModal').style.display = 'none';
      document.getElementById('eventForm').reset();
    }

    function submitEvent(url) {
      const eventid = document.getElementById('eventid').value.trim();
      const eventname = document.getElementById('eventname').value.trim();
      const eventdescription = document.getElementById('eventdescription').value.trim();
      fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            eventid,
            eventname,
            eventdescription
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            closeModal();
            eventsTable.ajax.reload();
          } else {
            alert('Error saving event: ' + (data.error || 'Unknown error'));
          }
        });
    }

    window.closeModal = closeModal;
  });
</script>

<?php
echo $OUTPUT->footer();
?>