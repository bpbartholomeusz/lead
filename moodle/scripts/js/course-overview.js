const COURSE_TYPE = 'LEAD Curriculumn';
let query = '';
let selectedCategory = '';
let coursesParticipants = [];

async function fetchCourseContent(courseId) {
  try {
    const tagsResponse = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        wstoken: SERVICE_TOKEN,
        wsfunction: 'core_course_get_contents',
        moodlewsrestformat: 'json',
        courseid: courseId,
      }),
    });

    const data = await response.json();
    console.log({ data });
  } catch (error) {
    console.error('Error fetching course IDs by tag:', error);
    return [];
  }
}

async function fetchCourses() {
  try {
    const payload = new URLSearchParams({
      wstoken: SERVICE_TOKEN,
      wsfunction: 'core_course_get_courses_by_field',
      moodlewsrestformat: 'json',
      field: 'category',
      value: categoryId,
    });

    const response = await fetch(API_URL, {
      method: 'POST',
      body: payload,
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();

    const courses = data.courses;

    const coursesIds = courses.map((course) => course.id);
    await fetchEnrolledUsersCount(coursesIds);

    // Call your render function here, if needed
    renderCourses(courses);

    return courses;
  } catch (error) {
    console.error('Error fetching tagged courses:', error);
    return [];
  }
}

function renderCourses(courses) {
  const courseList = document.getElementById('course-list');
  let items = '';

  console.log(coursesParticipants);

  const filteredCourses = filterCourses(courses);
  filteredCourses.forEach((course) => {
    items += `
          <div class="course-item">
            <div class="course-item-banner p-2" style="background-image: url('${decodeURIComponent(
              course.courseimage
            )}')">
              <header class="d-flex gap-2 justify-content-end">
                <!-- <span class="best-seller text-uppercase bold">Best seller</span> -->
                <span class="price ${
                  course.enrollmentmethods.includes('fee') ||
                  course.enrollmentmethods.includes('paypal')
                    ? 'paid'
                    : 'free'
                }">${
      course.enrollmentmethods.includes('fee') ||
      course.enrollmentmethods.includes('paypal')
        ? 'Paid'
        : 'Free'
    }</span>
              </header>
            </div>
            <div class="course-detail-wrapper">
              <div class="d-flex flex-column course-detail pb-2">
                <span class="course-title px-4 bold py-3">${
                  course.displayname
                }</span>
                <div class="course-type-wrapper text-xs">
                  <div class="d-flex gap-1 align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                      <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                    </svg>
                    <span>${COURSE_TYPE}</span>
                  </div>
                  <span class="d-flex gap-1 align-items-center">
                    <span>10/10/2024</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                      <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                      <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                  </span>
                </div>
              </div>
              <div class="d-flex py-2 px-3 justify-content-between category-wrapper text-xs text-gray">
                <div class="d-flex gap-1 align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                    <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z"/>
                  </svg>
                  <span>${course.categoryname}</span>
                </div>
                <div class="d-flex gap-1 align-items-center">
                  <span class="participant-count">${
                    coursesParticipants?.find((p) => p.courseid == course.id)
                      ?.count || 0
                  }</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                  </svg>
                </div>
              </div>
            </div>
            <div class="courser-footer">
              <a href="${window.location.origin}/course/view.php?id=${
      course.id
    }" class="uppercase">Enrol now</a>
            </div>
           </div>
      `;
  });

  courseList.innerHTML = items; // Set the generated HTML string to the courseList element
}
function filterCourses(courses) {
  return courses.filter((course) => {
    if (!query.trim()) return course;

    return course.fullname.toLowerCase().includes(query.toLowerCase());
  });
}

async function fetchCategories() {
  try {
    const response = await fetch(
      `${API_URL}?wsSERVICE_TOKEN=${SERVICE_TOKEN}&wsfunction=core_course_get_categories&moodlewsrestformat=json`
    );
    const categories = await response.json();

    // Create a map of category ID to its full hierarchical name
    const fullNameMap = {};

    // Function to recursively build the full category name
    function buildCategoryName(category) {
      if (fullNameMap[category.id]) {
        return fullNameMap[category.id];
      }

      const nameParts = [category.name];
      let parentCategory = categoryMap[category.parent];

      while (parentCategory) {
        if (fullNameMap[parentCategory.id]) {
          nameParts.unshift(fullNameMap[parentCategory.id]);
          break;
        }
        nameParts.unshift(parentCategory.name);
        parentCategory = categoryMap[parentCategory.parent];
      }

      const fullName = nameParts.join(' / ');
      fullNameMap[category.id] = fullName;

      return fullName;
    }

    // Create a map of category ID to category object
    const categoryMap = categories.reduce((map, category) => {
      map[category.id] = category;
      return map;
    }, {});

    // Build the options array with the full hierarchical name
    const optionsArray = categories.map((category) => {
      const fullName = buildCategoryName(category);
      return {
        id: category.id,
        fullName: fullName,
      };
    });

    // Sort options by full name
    optionsArray.sort((a, b) => a.fullName.localeCompare(b.fullName));

    // Build the final options string
    const options = optionsArray
      .map(
        (option) => `<option value="${option.id}">${option.fullName}</option>`
      )
      .join('');

    const defaultOption = `<option value="">All Category</option>`;

    // Set the innerHTML of the dropdown
    const dropdown = document.getElementById('course-category-dropdown');
    dropdown.innerHTML = defaultOption + options;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
}

async function initialize() {
  const courses = await fetchCourses('Alumni');
  renderCourses(courses);
  // fetchCourseContent(111);

  // Add event listener for the search bar
  document
    .querySelector('.course-search-bar')
    .addEventListener('keyup', async (event) => {
      query = event.target.value;
      const filteredCourses = filterCourses(courses);
      renderCourses(filteredCourses);
    });

  // Call the function to fetch and populate categories
  //fetchCategories();

  const dropdown = document.getElementById('course-category-dropdown');
  dropdown?.addEventListener('change', (event) => {
    selectedCategory = event.target.value;
    const filteredCourses = filterCourses(courses);
    renderCourses(filteredCourses);
  });
}

async function fetchEnrolledUsersCount(courseIds) {
  try {
    // Ensure courseIds is an array and convert it to a comma-separated string
    const ids = Array.isArray(courseIds) ? courseIds.join(',') : '';

    // Define the API endpoint
    const url =
      window.location.origin +
      '/scripts/api/get_courses_enrolled_users_count.php';

    // Make the POST request
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({ ids }),
    });

    // Check if the response is OK
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    // Parse the JSON response
    const data = await response.json();

    // Handle the data
    if (data.error) {
      console.error('Error:', data.error);
      return null;
    }

    coursesParticipants = data.data;
  } catch (error) {
    // Handle any errors that occurred during the fetch
    console.error('Fetch error:', error);
    return null;
  }
}

initialize();
