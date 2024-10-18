(function($) {
  $(document).ready(function() {

    // Responsive Tabs - No longer needed, in Moodle v4 they are built in the secondary menu
    // $('.nav-tabs').responsiveTabs();

    // Form control
    var $input = $('.form-control');

    function init($this) {
      $this.on('focus blur', function(e) {
          $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus'));
      }).trigger('blur');
    }
	
    if ($input.length) {
      init($input);
    }

    // Header Wrapper Height
    function setNavDrawerTop() {
      var headerWrapper = $('.header-wrapper');
      var navDrawer = $('#nav-drawer');

      if ( $(window).width() < 768 && $(headerWrapper).length ) {
        var headerWrapperHeight = headerWrapper.height();
        navDrawer.css({'top': `${ headerWrapperHeight }px`});
      } else if ( $(window).width() > 767 ) {
        navDrawer.css({'top': '0'});
      }
    }

    setNavDrawerTop();

    $(window).resize(function() {
      setNavDrawerTop();
    });

    // Course section completed
    function setCourseSectionStatus() {
      var courseSections = $('.show-completion-icons.is-enrolled .course-content .purity-collapsible > li');

      if (courseSections.length) {
        courseSections.each(function(index, item) {
          var sectionStatus = 1 // Completed
          var cardHeader = $(item).find('.card-header');
          var innerSections = $(item).find('.content ul.section > li');
          var multiPage = $(item).find('.card-header.multipage');

          var completedIcon = '<span class="purity-completion-icon section-completed"><span class="fa fa-check-circle" aria-hidden="true"></span></span>';
          var notCompletedIcon = '<span class="purity-completion-icon section-not-completed"><span class="fa fa-times-circle" aria-hidden="true"></span></span>';

          if (multiPage.length) {
            var activityCount = $(item).find('.section-summary-activities .activity-count.total-count');

            if (activityCount.length) {
              // progresstotal language string MUST include a semicolumn and a forward slash
              // in order to get the completed/total count
              var rawArr = activityCount.text().split(':');
              var countArr = rawArr[rawArr.length - 1].split('/').map(function(item) {
                return item.trim();
              });

              if (countArr[0] !== countArr[1]) {
                sectionStatus = 0;
              }
            }
          }
          else if (innerSections.length) {
            innerSections.each(function(index, item) {
              var isNewRendering = $(item).find('.activity-information').length;

              if (isNewRendering) {
                var innerSectionStatusManualElement = $(item).find('.activity-information .btn');
                var innerSectionStatusAutoElement = $(item).find('.activity-information .badge-light').length ? $(item).find('.activity-information .badge-light') : $(item).find('.activity-information .dropdown-toggle:not(.btn-success)');

                if (innerSectionStatusManualElement.length || innerSectionStatusAutoElement.length) {
                  if (innerSectionStatusManualElement.length && innerSectionStatusManualElement.attr('data-toggletype')) {
                    var innerSectionManualStatus = innerSectionStatusManualElement.attr('data-toggletype').toLowerCase().indexOf('manual:mark-done');
                  }
  
                  if (innerSectionStatusAutoElement.length || innerSectionManualStatus >= 0) {
                    sectionStatus = 0 // Not completed
                  }
                }
              } else {
                var innerSectionStatusElement = $(item).find('.actions img.icon');
                if (innerSectionStatusElement.length) {
                  var innerSectionAutoStatus = innerSectionStatusElement.attr('src').toLowerCase().indexOf('completion-auto-n');
                  var innerSectionManualStatus = innerSectionStatusElement.attr('src').toLowerCase().indexOf('completion-manual-n');
                  
  
                  if (innerSectionAutoStatus >= 0 || innerSectionManualStatus >= 0) {
                    sectionStatus = 0 // Not completed
                  }
                }
              }
            });
          }

          if (sectionStatus === 1) {
            $(cardHeader).append(completedIcon);
          } else {
            $(cardHeader).append(notCompletedIcon);
          }
        });
      }
    }

    function observeCourseSection() {
      var courseSectionsObserve = document.querySelector('.course-content .purity-collapsible');

      if (courseSectionsObserve) {
        var observer = new MutationObserver(function(mutations) {

          if (mutations[0].type === 'attributes' && ((mutations[0].target.tagName.toLowerCase() === 'img' && mutations[0].target.classList.contains("icon"))) || mutations[0].target.tagName.toLowerCase() === 'button') {
            var completionIcons = $('.purity-completion-icon');

            setTimeout(function() {
              if (completionIcons.length) {
                completionIcons.each(function(index, item) {
                  item.remove();
                });
              }

              setCourseSectionStatus();
            }, 500);
          }
        });
    
        observer.observe(courseSectionsObserve, {
          subtree: true,
          attributes: true
        });
      }
    }

    function initCourseSectionStatus() {
      var realLoggedIn = $('body.real-loggedin').length;
      var isCoursePage = $('body.path-course-view').length;
      var isEditing = $('body.editing').length;

      if (realLoggedIn && isCoursePage && !isEditing) {
        setCourseSectionStatus();
        observeCourseSection();
      }
    }

    initCourseSectionStatus();

    // Add scrolled class to body (Moodle 4)
    function scrollHandler() {
      const body = document.querySelector('body');
      const scrollY = window.pageYOffset || document.documentElement.scrollTop;
      if (scrollY >= window.innerHeight) {
          body.classList.add('scrolled');
      } else {
          body.classList.remove('scrolled');
      }
    }

    function initScroll() {
      window.addEventListener("scroll", scrollHandler);
    }

    initScroll();

    // CourseIndex custom active class on scroll
    function addCourseIndexActiveClassOnScroll() {
      var courseSection = $('.course-index-enabled .purity-collapsible .course-section');
      var headerHeight = $('.header-wrapper').outerHeight(true);
      var sectionHeight = courseSection.outerHeight(true);
      var offsetTopStart = headerHeight + 16;
      var offsetTopEnd = headerHeight - sectionHeight;

      courseSection.each(function (index, elem) {
        var offsetTop = $(elem)[0].getBoundingClientRect().top;

        if( offsetTop < offsetTopStart && offsetTop > offsetTopEnd ) {
          var sectionIndex = $(elem).attr('id').split('-')[1];

          $(".courseindex-section .courseindex-section-title").removeClass('active');
          $(".courseindex-section .courseindex-section-title#courseindexsection" + sectionIndex + "").addClass('active');
        }
      });
    };

    // Add scroll-margin-top style
    function addScrollMarginTopStyle() {
      var headerHeight = $('.header-wrapper').outerHeight(true);
      var scrollMarginTopValue = headerHeight + 10;

      var styleTag = $('<style>.path-course-view :target { scroll-margin-top:' +  scrollMarginTopValue + 'px' + '; }</style>')
      $('html > head').append(styleTag);
    }

    if ( $('.layout-modern').length ) {
      $('#page').on('scroll', function () {
        if ( !$('.course-index-enabled .purity-collapsible').length ) { return; }
        addCourseIndexActiveClassOnScroll();
      });
    }

    if ( $('.layout-classic').length ) {
      addScrollMarginTopStyle();

      $('.drawer-left .drawertoggle').click(function () {
          $('body').removeClass('drawer-open-index');
      });

      $('.drawer-left-toggle').click(function () {
          $('body').addClass('drawer-open-index');
      });

      $(window).on('scroll', function () {
        if ( !$('.course-index-enabled .purity-collapsible').length ) { return; }
        addCourseIndexActiveClassOnScroll();
      }); 
    }

  });
})(jQuery);