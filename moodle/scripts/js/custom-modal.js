document.addEventListener('DOMContentLoaded', async () => {
  let selectedGroupId = null;

  function toggleModal(modalId, criterias = '', groupName = '') {
    const modal = document.getElementById(modalId);
    const modalBody = document.querySelector('.join-group-modal-body');
    const modalTitle = document.querySelector('.modal-header .modal-title');

    if (modal) {
      modalBody.innerHTML =
        '<strong class="mb-2 d-block text-default">CRITERIAS</strong>' +
          criterias?.innerHTML || '';
      modal.classList.toggle('show');
      modalTitle.textContent = `Request to join group: ${groupName}`;
    }
  }

  function joinGroup(groupId = '') {
    if (!CURRENT_USER_ID) {
      // Redirect to login pag
      const loginUrl = window.location.origin + `/login/index.php`;
      window.location.href = loginUrl;
      return;
    }

    if (!groupId) {
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
            toggleModal('join-group-modal');
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

  joinButtons.forEach((button) => {
    const groupId = button.dataset.groupId;

    button.addEventListener('click', () => {
      const criterias = button
        .closest('.group-item')
        .querySelector('.criterias-wrapper');
      const groupName =
        button.closest('.group-item').querySelector('h2')?.textContent || '';

      selectedGroupId = parseInt(groupId);

      toggleModal('join-group-modal', criterias, groupName);
    });
  });

  const closeButtons = document.querySelectorAll('.modal-close');
  closeButtons.forEach((button) => {
    button.addEventListener('click', () => {
      selectedGroupId = null;
      toggleModal('join-group-modal');
    });
  });

  const confirmJoinGroup = document.querySelector('.confirm-join-group');
  confirmJoinGroup?.addEventListener('click', () => {
    // console.log({ selectedGroupId });
    joinGroup(selectedGroupId);
  });
});
