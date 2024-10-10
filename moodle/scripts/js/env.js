var SERVICE_TOKEN = '4403d4e0966f92de1b275114ab20274e';
var API_URL = 'https://moodle.leadcurriculum.cloud/webservice/rest/server.php';
var CURRENT_USER_ID = '';
var SELECTED_CATEGORY = [];

document.addEventListener('DOMContentLoaded', async () => {
  async function fetchUserId() {
    try {
      const response = await fetch(
        'https://moodle.leadcurriculum.cloud/scripts/api/get_current_user_id.php'
      );
      const data = await response.json();
      CURRENT_USER_ID = data.userid;

      // if (!CURRENT_USER_ID) {
      //   showSignUpButton();
      // } else {
      // if (window.location.pathname === '/login/index.php') {
      //   window.location.href =
      //     'https://moodle.leadcurriculum.cloud/my/courses.php';
      // }
      // }
    } catch (error) {
      console.error('Error fetching user ID:', error);
    }
  }

  fetchUserId();

  function showSignUpButton() {
    const usermenuLogin = document.querySelector('.usermenu .login');

    if (usermenuLogin) {
      const signupButton = `<a class="btn btn-sm bg-dark-pink text-white" href="https://moodle.leadcurriculum.cloud/login/signup.php">Sign up</a>`;

      usermenuLogin.children[0].insertAdjacentHTML('afterend', signupButton);
    }
  }
});
