document.addEventListener('DOMContentLoaded', () => {
  const slides = document.querySelector('.slides');
  const slide = document.querySelectorAll('.slide');
  let currentIndex = 0;

  document
    .querySelector('.video-slider .next')
    ?.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % slide.length;
      slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    });

  document
    .querySelector('.video-slider .prev')
    ?.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + slide.length) % slide.length;
      slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    });
});
