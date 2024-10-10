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
 * Session Booking Plugin
 *
 * @package    local_booking
 * @author     Mustafa Hajjar (mustafahajjar@gmail.com)
 * @copyright  BAVirtual.co.uk © 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_booking\local\session\entities;

use local_booking\local\participant\entities\instructor;
use local_booking\local\participant\entities\participant;
use local_booking\local\participant\entities\student;
use local_booking\local\subscriber\entities\subscriber;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

/**
 * Class representing a course exercise session action.
 *
 * @copyright  BAVirtual.co.uk © 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class action implements action_interface {

    /**
     * @var string $type The type of this action.
     */
    protected $type;

    /**
     * @var boolean $enabled The status of this action.
     */
    protected $enabled = true;

    /**
     * @var url $url The name of this action.
     */
    protected $url;

    /**
     * @var string $type The name of this action.
     */
    protected $name;

    /**
     * @var int $exerciseid The exerciseid id associated with the action.
     */
    protected $exerciseid;

    /**
     * @var string $tooltip The action's tooltip explaining its status.
     */
    protected $tooltip;

    /**
     * Constructor.
     *
     * @param subscriber $course     The subscribing course.
     * @param student    $student    The student behind the action.
     * @param string     $actiontype The type of action requested.
     * @param int        $refid      The reference exercise id if available.
     */
    public function __construct(subscriber $course, student $student, string $actiontype, int $refid = 0) {

        $gradexercise = $course->get_graduation_exercise();
        $exerciseid = $student->get_next_exercise();
        $enabled =  $student->is_active();
        $tooltip = '';
        $params = [];

        // get the next student action if this is not a cancel booking action
        switch ($actiontype) {

            // book action
            case 'book':

                // check if the session to book is the next exercise after passing the current session or the same
                $lastgrade = $student->get_last_grade();
                $passedlastexercise = (!empty($lastgrade) ? $lastgrade->is_passed() : true);
                $exerciseid = $passedlastexercise ? $exerciseid : $student->get_current_exercise();
                $tooltip = get_string('actionbooksession', 'local_booking');

                // get action enabled status by checking if there are more exercises to book and
                // if the user is an examiner in the case of graduation skill tests

                // get action enabled status and tooltip
                if (!$student->has_completed_lessons()) {

                    $enabled = false;
                    $tooltip = get_string('actiondisabledincompletelessonstooltip', 'local_booking');

                // check if student completed all lessons (graduated)
                } else if ($student->graduated()) {

                    $enabled = false;
                    $tooltip = get_string('actiondisabledexercisescompletedtooltip', 'local_booking');

                // check next exercise permissions
                } else {

                    if (!$enabled = \has_capability('mod/assign:grade', \context_module::instance($exerciseid))) {
                        $tooltip = get_string(($exerciseid == $gradexercise ? 'actiondisabledexaminersonlytooltip' : 'actiondisabledseniorsonlytooltip'), 'local_booking');
                    }

                }

                // Book action takes the instructor to the week of the firs slot or after waiting period
                $actionurl = '/local/booking/view.php';
                $params = [
                    'exid'   => $exerciseid,
                    'action' => 'confirm',
                    'view'   => 'user',
                ];
                $name = get_string('book', 'local_booking');

                break;

            case 'grade':

                // get exercise to be graded
                if ($grade = $student->get_current_grade()) {
                    $exerciseid = $grade->finalgrade > 1 ? $exerciseid : $student->get_current_exercise();
                } else {
                    $exerciseid = $student->get_current_exercise();
                }

                // set grading url
                $actionurl = '/local/booking/assign.php';
                $params = ['exeid' => $exerciseid];
                $name = get_string('grade', 'grades');
                $tooltip = get_string('actiongradesession', 'local_booking');

                // check if the instructor can grade the exercise
                $submissionsatisfied = $student->has_submitted_assignment($exerciseid);
                if (!$enabled = \has_capability('mod/assign:grade', \context_module::instance($exerciseid)) && $submissionsatisfied) {
                    $tag = !$submissionsatisfied ? 'actiondisabledsubmissionmissingtooltip' : ($exerciseid == $gradexercise ? 'actiondisabledexaminersonlytooltip' : 'actiondisabledseniorsonlytooltip');
                    $tooltip = get_string($tag, 'local_booking');
                }

                break;

            case 'graduate':

                // graduate the student's next grading action, which will be 'grade' for exercises
                // set url to the student's skill test form
                $actionurl = '/local/booking/certify.php';
                $name = get_string($actiontype, 'local_booking');

                // check if the certifer is the examiner
                if ($student->has_completed_coursework() || $student->get_current_exercise() == $gradexercise) {
                    global $USER;

                    // check if graduation capability is allowed
                    $context = \context_system::instance();
                    $isadmin = has_capability('moodle/site:config', $context);
                    $examinerid = $student->get_grade($gradexercise)->usermodified;
                    $logbook = $student->get_logbook(true);
                    $hasexamlogentry = !empty($logbook->get_logentry_by_sessionid($refid));
                    $enabled = (\has_capability('mod/assign:grade', \context_module::instance($gradexercise)) && $examinerid == $USER->id && $hasexamlogentry) || $isadmin;

                    // evaluate tooltip based on ability to graduate the student
                    if (!$enabled) {
                        if ($examinerid != $USER->id)
                            $tooltip = get_string('actiondisabledwrongexaminerstooltip', 'local_booking', participant::get_fullname($examinerid));
                        elseif (!$hasexamlogentry)
                            $tooltip = get_string('actiondisablednologentrytooltip', 'local_booking', participant::get_fullname($examinerid));
                        else
                            $tooltip = get_string('actiondisabledexaminersonlytooltip', 'local_booking');
                    } else
                        $tooltip = get_string('action' . $actiontype . 'tooltip', 'local_booking', ['studentname'=>$student->get_name(false), 'examname'=>$course->get_graduation_exercise(true)]);
                }

                break;

            case 'cancel':

                // cancel action
                $exerciseid = $refid;
                $actiontype = 'cancel';
                $actionurl = '/local/booking/view.php';
                $name = get_string('bookingcancel', 'local_booking');
                $tooltip = get_string('actioncancelsession', 'local_booking');

                break;

        }

        $params += ['courseid' => $course->get_id(), 'userid' => $student->get_id()];
        $this->url = new moodle_url($actionurl, $params);
        $this->type = $actiontype;
        $this->name = $name;
        $this->exerciseid = $exerciseid;
        $this->enabled = $enabled;
        $this->tooltip = $tooltip;

    }

    /**
     * Get the type of the action.
     *
     * @return string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Get the URL of the action.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Get the name of the action.
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get the exercise id of the action.
     *
     * @return int
     */
    public function get_exerciseid() {
        return $this->exerciseid;
    }

    /**
     * Get the action's tooltip explaining its status.
     *
     * @return string
     */
    public function get_tooltip() {
        return $this->tooltip;
    }

    /**
     * Get the action's status.
     *
     * @return boolean the action's status
     */
    public function is_enabled() {
        return $this->enabled;
    }
}
