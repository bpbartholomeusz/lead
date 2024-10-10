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
 * Featured coures block main class.
 *
 * @package    block_featuredcourses
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/course/renderer.php');

class block_featuredcourses extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_featuredcourses');
    }

    public function get_content() {
        global $CFG, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        $this->content->text = '';

        $courses = self::get_featured_courses();
        $chelper = new coursecat_helper();
        $this->content->text .= '<div class="card-deck">';
        foreach ($courses as $course) {

            $course = new core_course_list_element($course);

            $content = '<div class="card">';

            // Display course overview files.
            $contentimages = $contentfiles = '';
            $url = \core_course\external\course_summary_exporter::get_course_image($course);
            if (!$url) {
                $url = $OUTPUT->get_generated_image_for_id($course->id);
            }
            $contentimages .= html_writer::empty_tag('img', array('src' => $url, 'style' => 'max-height: 10rem;'), ['class' => 'card-img-top']);
            $content .= $contentimages;

            $content .= '<div class="card-body">';

            $coursename = $chelper->get_course_formatted_name($course);
            $coursenamelink = html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                                                $coursename, array('class' => $course->visible ? '' : 'dimmed'));

            $content .= html_writer::tag('h5', $coursenamelink, array('class' => 'card-title'));

            if ($course->has_summary()) {
                $content .= html_writer::start_tag('p', array('class' => 'card-text'));
                $content .= $chelper->get_course_formatted_summary($course,
                        array('overflowdiv' => true, 'noclean' => true, 'para' => false));
                $content .= html_writer::end_tag('div');
            }

            $this->content->text .= $content. '</div></div>';
        }
        $this->content->text .= "</div>";

        return $this->content;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_multiple() {
          return false;
    }

    public function has_config() {
        return false;
    }

    public function cron() {
        return true;
    }

    public static function get_featured_courses() {
        global $DB;

        $sql = 'SELECT c.id, c.shortname, c.fullname, fc.sortorder
                  FROM {block_featuredcourses} fc
                  JOIN {course} c
                    ON (c.id = fc.courseid)
              ORDER BY sortorder';
        return $DB->get_records_sql($sql);
    }

    public static function delete_featuredcourse($courseid) {
        global $DB;
        return $DB->delete_records('block_featuredcourses', array('courseid' => $courseid));
    }
}
