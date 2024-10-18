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
 * Configurable Reports a Moodle block for creating customizable reports
 *
 * @copyright  2020 Juan Leyva <juan@moodle.com>
 * @package    block_configurable_reports
 * @author     Juan leyva <http://www.twitter.com/jleyvadelgado>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot . '/blocks/configurable_reports/plugin.class.php');

/**
 * Class plugin_finalgradeincurrentcourse
 *
 * @package   block_configurable_reports
 * @author    Juan leyva <http://www.twitter.com/jleyvadelgado>
 */
class plugin_finalgradeincurrentcourse extends plugin_base {

    /**
     * Init
     *
     * @return void
     */
    public function init(): void {
        $this->fullname = get_string('finalgradeincurrentcourse', 'block_configurable_reports');
        $this->form = true;
        $this->reporttypes = ['users'];
    }

    /**
     * Summary
     *
     * @param object $data
     * @return string
     */
    public function summary(object $data): string {
        return format_string($data->columname);
    }

    /**
     * Execute
     *
     * @param array $data
     * @param object $row
     * @param object $user
     * @param int $courseid
     * @return string
     */
    public function execute($data, $row, $user, $courseid) {
        global $CFG;

        // Data -> Plugin configuration data.
        // Row -> Complet course row c->id, c->fullname, etc.
        $userid = $row->id;
        require_once($CFG->libdir . '/gradelib.php');
        require_once($CFG->dirroot . '/grade/querylib.php');

        if ($grade = grade_get_course_grade($userid, $courseid)) {
            return $grade->grade;
        }

        return '';
    }

}