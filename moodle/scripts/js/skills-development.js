document.addEventListener('DOMContentLoaded', function () {
  // url param "skill"

  const urlParams = new URLSearchParams(window.location.search);
  const SELECTED_SKILL = urlParams.get('skill') || 'All Skills';
  // console.log({ SELECTED_SKILL });
  // console.log({ SELECTED_CATEGORY });

  document.querySelector('h1')?.textContent = SELECTED_SKILL;
});
