document.addEventListener('DOMContentLoaded', async () => {
  // Function to join a group
  function joinGroup(groupId) {
    if (!CURRENT_USER_ID) {
      // Redirect to login pag
      const loginUrl = `${window.location.origin}/login/index.php`;
      window.location.href = loginUrl;
      return;
    }

    const body = new URLSearchParams({
      wstoken: SERVICE_TOKEN,
      wsfunction: 'core_group_add_group_members',
      moodlewsrestformat: 'json',
      'members[0][groupid]': groupId,
      'members[0][userid]': CURRENT_USER_ID,
    });

    fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: body.toString(),
    })
      .then((response) => response.json())
      .then((data) => {
        //if data is null, it means successfully joined the group
        if (!data) {
          setTimeout(() => {
            window.location.href =
              window.location.origin + '/message/index.php';
          }, 500);
        }
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }

  const joinButtons = document.querySelectorAll('.join-group-btn');

  joinButtons?.forEach((joinButton) => {
    joinButton.addEventListener('click', () => {
      const groupId = joinButton.dataset.groupId;

      if (!groupId) {
        console.error('Group ID not found in data attribute');
        return;
      }

      joinGroup(groupId);
    });
  });
});
