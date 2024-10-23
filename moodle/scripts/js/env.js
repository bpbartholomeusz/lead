var SERVICE_TOKEN = '4403d4e0966f92de1b275114ab20274e';
var API_URL = window.location.origin + '/webservice/rest/server.php';
var CURRENT_USER_ID = '';
var SELECTED_CATEGORY = [];

document.addEventListener('DOMContentLoaded', async () => {
  async function fetchUserId() {
    try {
      const response = await fetch(
        window.location.origin + '/scripts/api/get_current_user.php'
      );
      const data = await response.json();
      CURRENT_USER_ID = data.userid;

      if (data.is_site_admin) {
        appendMenuItem();
      }
    } catch (error) {
      console.error('Error fetching user ID:', error);
    }
  }

  fetchUserId();

  function appendMenuItem() {
    // Create a new `li` element with the required HTML structure
    const newMenuItem = document.createElement('li');
    newMenuItem.className = 'dropdown nav-item show';
    newMenuItem.setAttribute('role', 'none');
    newMenuItem.setAttribute('data-forceintomoremenu', 'false');
    newMenuItem.id = 'yui_3_18_1_1_1729408834891_20';

    newMenuItem.innerHTML = `
        <a class="dropdown-toggle nav-link" id="drop-down-6714af42c836c" role="menuitem" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="#" aria-controls="drop-down-menu-6714af42c836c" tabindex="-1">
            Requests
        </a>
        <div class="dropdown-menu" role="menu" id="drop-down-menu-6714af42c836c" aria-labelledby="drop-down-6714af42c836c">
            <a class="dropdown-item" role="menuitem" href="${window.location.origin}/local/proposed_groups" tabindex="-1">
                New Group Proposals
            </a>
            <a class="dropdown-item" role="menuitem" href="${window.location.origin}/local/proposed_forums" tabindex="-1">
                New Forum Proposals
            </a>
            <a class="dropdown-item" role="menuitem" href="${window.location.origin}/local/impact_network" tabindex="-1">
                Impact Network
            </a>
        </div>
    `;

    // Create `groupNavigation` element
    const groupNavigation = document.createElement('li');
    groupNavigation.className = 'nav-item';
    groupNavigation.setAttribute('role', 'none');
    groupNavigation.setAttribute('data-forceintomoremenu', 'false');
    groupNavigation.innerHTML = `
      <a role="menuitem" class="nav-link" href="${window.location.origin}/group/index.php?id=1" tabindex="-1">
        Groups
      </a>
    `;

    // Append the new menu item and group navigation as second-to-last and third-to-last items in the `.primary-navigation ul`
    const primaryNav = document.querySelector('.primary-navigation ul');
    if (primaryNav) {
      const lastItem = primaryNav.lastElementChild; // Find the last child element
      if (lastItem) {
        primaryNav.insertBefore(newMenuItem, lastItem); // Insert newMenuItem before the last item
        primaryNav.insertBefore(groupNavigation, lastItem); // Insert groupNavigation after newMenuItem
      } else {
        primaryNav.appendChild(newMenuItem); // If no last child exists, just append newMenuItem
        primaryNav.appendChild(groupNavigation); // Append groupNavigation next
      }
      console.log('Menu items added successfully');
    } else {
      console.error('Navigation ul not found');
    }
  }
});
