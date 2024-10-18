require(['jquery'], function($) {
    $(document).on('show.bs.modal', '#eventmodal', function(e) {
        // Get the event id
        var eventid = $(e.relatedTarget).data('eventid');

        // Check if the user is already registered for this event (AJAX request)
        $.ajax({
            url: M.cfg.wwwroot + '/local/eventregistration/check_registration.php',
            type: 'POST',
            data: {eventid: eventid},
            success: function(response) {
                if (!response.registered) {
                    // If not registered, add the Register button
                    $('#eventmodal .modal-footer').append('<button id="registerButton" class="btn btn-primary">Register</button>');
                } else {
                    $('#eventmodal .modal-footer').append('<p>You are already registered for this event.</p>');
                }
            }
        });

        // Handle the registration button click
        $(document).on('click', '#registerButton', function() {
            $.ajax({
                url: M.cfg.wwwroot + '/local/eventregistration/register.php',
                type: 'POST',
                data: {eventid: eventid},
                success: function(response) {
                    alert('You have successfully registered!');
                    $('#registerButton').remove(); // Remove the button after registration
                    $('#eventmodal .modal-footer').append('<p>You are now registered.</p>');
                }
            });
        });
    });
});
