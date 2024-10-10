<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course renderer.
 */

namespace theme_purity\output\core;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use html_writer;
use core_course_category;
use coursecat_helper;
use stdClass;
use core_course_list_element;
use lang_string;

require_once($CFG->dirroot . '/course/renderer.php');

/**
 * Course renderer class.
 */
class course_renderer extends \core_course_renderer {

    /**
     * Renders the list of courses
     *
     * This is internal function, please use {@link core_course_renderer::courses_list()} or another public
     * method from outside of the class
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;

        $theme = \theme_config::load('purity');

        if ($theme->settings->listcourseslayout == 'list') {
            return parent::coursecat_courses($chelper, $courses, $totalcount);
        }

        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // prepare content of paging bar if it is needed
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // there are more results that can fit on one page
            if ($paginationurl) {
                // the option paginationurl was specified, display pagingbar
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // the option for 'View more' link was specified, display more link
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // there are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode
            $pagingbar = html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => $CFG->coursesperpage)),
                get_string('showperpage', '', $CFG->coursesperpage)), array('class' => 'paging paging-showperpage'));
        }

        // display list of courses
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 1;
        $content .= html_writer::start_tag('div', array('class' => 'card-deck dashboard-card-deck'));

        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course);
            $coursecount ++;
        }

        $content .= html_writer::end_tag('div');

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // .courses
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@link core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_list_element|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        $theme = \theme_config::load('purity');

        if ($theme->settings->listcourseslayout == 'list') {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }
        $content = '';
        $classes = trim('card dashboard-card');
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $classes .= ' collapsed';
        }

        // .coursebox
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));

        // $content .= html_writer::start_tag('div', array('class' => 'info'));
        // $content .= $this->course_name($chelper, $course);
        // $content .= $this->course_enrolment_icons($course);
        // $content .= html_writer::end_tag('div');

        // $content .= html_writer::start_tag('div', array('class' => 'content'));
        // $content .= $this->coursecat_coursebox_content($chelper, $course);
        // $content .= html_writer::end_tag('div');

        // $content .= html_writer::end_tag('div'); // .coursebox

        $content .= $this->coursecat_coursebox_content($chelper, $course);

        $content .= html_writer::end_tag('div'); // End coursebox.

        return $content;
    }

    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|core_course_list_element $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        $theme = \theme_config::load('purity');

        if ($theme->settings->listcourseslayout == 'list') {
            return parent::coursecat_coursebox_content($chelper, $course);
        }

        // if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
        //     return '';
        // }

        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $coursename = $chelper->get_course_formatted_name($course);
        $courselink = new moodle_url('/course/view.php', array('id' => $course->id));
        $coursenamelink = html_writer::link($courselink, $coursename, array('class' => $course->visible ? '' : 'dimmed'));

        // Price
        $course_price_show_class = $theme->settings->showcourseprice;
        $course_price_accent_color = $theme->settings->coursepriceaccentcolor;
        $course_has_price = 0;
        $enrol_instances = enrol_get_instances($course->id, true);
        foreach ($enrol_instances as $key => $instance) {
            if (!empty($instance->cost)) {
                $course_cost = $instance->cost;
                $course_currency = $instance->currency;
                $course_enrol_method = $instance->enrol;
                $course_has_price = 1;
            }
        }
        if ($course_has_price) {
            if (!empty($theme->settings->coursepricecurrencysymbol)) {
                $course_price_symbol = $theme->settings->coursepricecurrencysymbol;
                $course_price = $course_price_symbol . '' . $course_cost;
            } else {
                $course_price = $course_cost . ' ' . $course_currency;
            }

            $course_price_o = '<span class="course-price-badge bg-' . $course_price_accent_color . ' ' . $course_price_show_class . '">' . $course_price . '</span>';
        } else {
            $course_price_o = '<span class="course-price-badge course-free bg-' . $course_price_accent_color . ' ' . $course_price_show_class . '">' . get_string('course_cost_free', 'theme_purity') .'</span>';
        }

        $content = $this->get_course_summary_image($course, $courselink);

        if ($theme->settings->showcourseprice == 'show-top') {
            $content .= $course_price_o;
        }

        $content .= html_writer::start_tag('div', array('class' => 'card-body course-info-container'));

        $content .= html_writer::start_tag('div', array('class' => 'd-flex align-items-start'));
        $content .= html_writer::start_tag('div', array('class' => 'w-100 text-truncate'));

        $content .= html_writer::start_tag('div', array('class' => 'text-muted muted d-flex mb-1 flex-wrap'));
        $content .= html_writer::start_tag('span', array('class' => 'text-truncate'));
        if ($cat = core_course_category::get($course->category, IGNORE_MISSING)) {
            $content .= html_writer::link(new moodle_url('/course/index.php', ['categoryid' => $cat->id]),
                    $cat->get_formatted_name());
        }
        $content .= html_writer::end_tag('span');
        $content .= html_writer::end_tag('div');

        $content .= "<h4 class='course-title'>";
        // Print enrolmenticons.
        if ($icons = enrol_get_course_info_icons($course)) {
          $enrolment_icons = "<ul class='icon-list'>";
          foreach ($icons as $pixicon) {
              $enrolment_icons .= "<li>";
              $enrolment_icons .= $this->render($pixicon);
              $enrolment_icons .= "</li>";
          }
          $enrolment_icons .= "</ul>";
        }
        $content .= "<span class='title-text' title='$coursename'>". $coursenamelink ."</span>";
        $content .= "</h4>";

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');

        $content .= $this->course_summary($chelper, $course);
        $content .= $this->course_custom_fields($course);

        $content .= '<div class="icons-price-container">';
        $content .= $enrolment_icons;
        if ($theme->settings->showcourseprice == 'show-bottom') {
            $content .= $course_price_o;
        }
        $content .= '</div>';

        $content .= html_writer::end_tag('div'); // end card-body

        $content .= $this->course_contacts($course);
        return $content;
    }

    /**
     * Returns HTML to display course contacts.
     *
     * @param core_course_list_element $course
     * @return string
     */
    protected function course_contacts(core_course_list_element $course) {
    	global $CFG, $DB;

        $theme = \theme_config::load('purity');

        if ($theme->settings->listcourseslayout == 'list') {
            return parent::course_contacts($course);
        }

        $content = '';
        if ($course->has_course_contacts()) {
            $content .= html_writer::start_tag('div', ['class' => 'card-footer']);

            $content .= html_writer::start_tag('span', ['class' => 'course-contacts-text']);
            $content .= get_string('coursecontacts', 'theme_purity') . ':';
            $content .= html_writer::end_tag('span');

            $instructors = $course->get_course_contacts();
            foreach ($instructors as $key => $instructor) {
                $name = $instructor['username'];
                $url = $CFG->wwwroot.'/user/profile.php?id='.$key;
                $picture = $this->get_user_picture($DB->get_record('user', array('id' => $key)));

                $content .= "<a href='{$url}' class='course-contact' data-toggle='tooltip' title='{$name}'>";
                $content .= "<img src='{$picture}' class='rounded-circle' alt='{$name}'/>";
                $content .= "</a>";
            }

            $content .= html_writer::end_tag('div');
        }

        return $content;
    }

    /**
     * Returns the first course's summary image
     *
     * @param stdClass $course the course object
     * @return string
     */
    protected function get_course_summary_image($course, $courselink) {
        global $CFG;

        $content = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
           if ($isimage) {
	           	$content .= "<a href='$courselink'>";
	            $content .= "<div class='card-img dashboard-card-img' style='background-image: url($url);'></div>";
	            $content .= "</a>";
            }

        }

        if (empty($content)) {
            $url = $CFG->wwwroot . "/theme/purity/pix/default_course.jpg";

           	$content .= "<a href='$courselink'>";
            $content .= "<div class='card-img dashboard-card-img' style='background-image: url($url);'></div>";
            $content .= "</a>";

        }

        return $content;
    }

    /**
     * Returns the user picture
     *
     * @param null $userobject
     * @param int $imgsize
     *
     * @return \moodle_url
     * @throws \coding_exception
     */
    public static function get_user_picture($userobject = null, $imgsize = 100) {
        global $USER, $PAGE;

        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new \user_picture($userobject);

        $userimg->size = $imgsize;

        return $userimg->get_url($PAGE);
    }

}