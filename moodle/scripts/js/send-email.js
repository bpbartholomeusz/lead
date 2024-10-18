document.addEventListener('DOMContentLoaded', () => {
  const userList = document.getElementById('userList');
  const searchInput = document.getElementById('searchInput');
  const selectedUsersDiv = document.getElementById('selectedUsers');
  const selectedUsers = new Set();

  async function fetchUsers() {
    const body = new URLSearchParams({
      wstoken: SERVICE_TOKEN,
      wsfunction: 'core_user_get_users',
      moodlewsrestformat: 'json',
      'criteria[0][key]': 'auth',
      'criteria[0][value]': 'manual',
    });

    try {
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
      });

      const data = await response.json();
      return data.users || [];
    } catch (error) {
      console.error('Error fetching users:', error);
      return [];
    }
  }

  function renderUserList(users, filter = '') {
    userList.innerHTML = '';
    const filteredUsers = users.filter(
      (user) =>
        user.firstname.toLowerCase().includes(filter.toLowerCase()) ||
        user.lastname.toLowerCase().includes(filter.toLowerCase())
    );
    filteredUsers.forEach((user) => {
      const userDiv = document.createElement('div');
      userDiv.textContent = `${user.firstname} ${user.lastname}`;
      userDiv.dataset.id = user.id;
      userDiv.addEventListener('click', () => selectUser(user));
      userList.appendChild(userDiv);
    });
  }

  function selectUser(user) {
    if (selectedUsers.has(user.id)) {
      selectedUsers.delete(user.id);
      document
        .querySelector(`[data-id="${user.id}"]`)
        .classList.remove('selected');
    } else {
      selectedUsers.add(user.id);
      document
        .querySelector(`[data-id="${user.id}"]`)
        .classList.add('selected');
    }
    updateSelectedUsers();
  }

  function updateSelectedUsers() {
    selectedUsersDiv.innerHTML = '';
    selectedUsers.forEach((id) => {
      const user = users.find((user) => user.id === id);
      if (user) {
        const userDiv = document.createElement('div');
        userDiv.textContent = `${user.firstname} ${user.lastname}`;
        selectedUsersDiv.appendChild(userDiv);
      }
    });
  }

  searchInput.addEventListener('input', (event) => {
    renderUserList(users, event.target.value);
  });

  let users = [];
  fetchUsers().then((fetchedUsers) => {
    users = fetchedUsers;
    console.log({ users });
    renderUserList(users);
  });
});
