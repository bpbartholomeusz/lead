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
 * Class for displaying students profile.
 *
 * @package    local_booking
 * @author     Mustafa Hajjar (mustafahajjar@gmail.com)
 * @copyright  BAVirtual.co.uk © 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_booking\external;

defined('MOODLE_INTERNAL') || die();

use core\external\exporter;
use local_booking\local\participant\entities\participant;
use local_booking\local\participant\entities\student;
use local_booking\local\subscriber\entities\subscriber;
use local_booking\output\views\base_view;
use renderer_base;
use moodle_url;

/**
 * Class for displaying student profile page.
 *
 * @package    local_booking
 * @author     Mustafa Hajjar (mustafahajjar@gmail.com)
 * @copyright  BAVirtual.co.uk © 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class student_profile_exporter extends exporter {

    /**
     * @var subscriber $subscriber The plugin subscribing course
     */
    protected $subscriber;

    /**
     * @var student $student The student user of the profile
     */
    protected $student;

    /**
     * @var int $courseid The id of the active course
     */
    protected $courseid;

    /**
     * Constructor.
     *
     * @param mixed $data An array of student profile data.
     * @param array $related Related objects.
     */
    public function __construct($data, $related) {

        $url = new moodle_url('/local/booking/view.php', [
                'courseid' => $data['courseid']
            ]);

        $data['url'] = $url->out(false);
        $data['contextid'] = $related['context']->id;
        $data['userid'] = $data['userid'];
        $this->courseid = $data['courseid'];
        $this->subscriber = $data['subscriber'];
        $this->student = $this->subscriber->get_student($data['userid'], true);

        parent::__construct($data, $related);
    }

    protected static function define_properties() {
        return [
            'url' => [
                'type' => PARAM_URL,
            ],
            'contextid' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'courseid' => [
                'type' => PARAM_INT,
            ],
            'userid' => [
                'type' => PARAM_INT,
            ],
        ];
    }

    /**
     * Return the list of additional properties.
     *
     * @return array
     */
    protected static function define_other_properties() {
        return [
            'fullname' => [
                'type' => PARAM_RAW,
            ],
            'timezone' => [
                'type' => PARAM_RAW,
            ],
            'fleet' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'sim1' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'sim2' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'noshows' => [
                'type' => PARAM_URL,
                'optional' => true
            ],
            'moodleprofileurl' => [
                'type' => PARAM_URL,
            ],
            'recency' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'courseactivity' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'slots' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'modulescompleted' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'enroldate' => [
                'type' => PARAM_RAW,
            ],
            'lastlogin' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'lastgraded' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'lastlesson' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'lastlessoncompleted' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'graduationstatus' => [
                'type' => PARAM_RAW,
                'optional' => true,
            ],
            'qualified' => [
                'type' => PARAM_BOOL,
                'default' => false,
            ],
            'endorsed' => [
                'type' => PARAM_BOOL,
                'default' => false,
            ],
            'endorsername' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'endorser' => [
                'type' => PARAM_INT,
                'optional' => true
            ],
            'endorsementlocked' => [
                'type' => PARAM_BOOL,
            ],
            'endorsementmgs' => [
                'type' => PARAM_RAW,
                'optional' => true
            ],
            'recommendationletterlink' => [
                'type' => PARAM_URL,
            ],
            'suspended' => [
                'type'  => PARAM_BOOL,
            ],
            'onholdrestrictionenabled' => [
                'type'  => PARAM_BOOL,
            ],
            'onhold' => [
                'type'  => PARAM_BOOL,
            ],
            'onholdgroup' => [
                'type'  => PARAM_RAW,
            ],
            'keepactive' => [
                'type'  => PARAM_BOOL,
            ],
            'keepactivegroup' => [
                'type'  => PARAM_RAW,
            ],
            'waitrestrictionenabled' => [
                'type'  => PARAM_BOOL,
            ],
            'restrictionoverride' => [
                'type'  => PARAM_BOOL,
            ],
            'admin' => [
                'type'  => PARAM_BOOL,
            ],
            'hasexams' => [
                'type'  => PARAM_BOOL,
            ],
            'requiresevaluation' => [
                'type'  => PARAM_BOOL,
            ],
            'loginasurl' => [
                'type' => PARAM_URL,
            ],
            'outlinereporturl' => [
                'type' => PARAM_URL,
            ],
            'completereporturl' => [
                'type' => PARAM_URL,
            ],
            'logbookurl' => [
                'type' => PARAM_URL,
            ],
            'mentorreporturl' => [
                'type' => PARAM_URL,
            ],
            'theoryexamreporturl' => [
                'type' => PARAM_URL,
            ],
            'practicalexamreporturl' => [
                'type' => PARAM_URL,
            ],
            'tested' => [
                'type' => PARAM_BOOL,
                'default' => false,
            ],
            'coursemodules' => [
                'type' => exercise_name_exporter::read_properties_definition(),
                'multiple' => true,
            ],
            'sessions' => [
                'type' => booking_session_exporter::read_properties_definition(),
                'multiple' => true,
            ],
            'comment' => [
                'type' => PARAM_TEXT,
                'defaul' => '',
            ],
        ];
    }

    /**
     * Get the additional values to inject while exporting.
     *
     * @param renderer_base $output The renderer.
     * @return array Keys are the property names, values are their values.
     */
    protected function get_other_values(renderer_base $output) {
        global $USER, $CFG;

        // moodle user object
        $studentid = $this->student->get_id();
        $moodleuser = \core_user::get_user($studentid, 'timezone');

        // student current lesson
        $exerciseid = $this->student->get_current_exercise();
        $currentlesson = array_values($this->subscriber->get_lesson_by_exerciseid($exerciseid))[1];

        // module completion information
        $usermods = $this->student->get_priority()->get_completions();
        $coursemods = count($this->subscriber->get_modules());
        $modsinfo = [
            'usermods' => $usermods,
            'coursemods' => $coursemods,
            'percent' => round(($usermods*100)/$coursemods)
        ];

        // no shows
        $noshows = get_string('none');
        $noshowdates = [];
        if ($noshowslist = array_column($this->student->get_noshow_bookings(), 'starttime')) {
            foreach ($noshowslist as $noshowdate) {
                $noshowdates[] = (new \DateTime('@' . $noshowdate))->format('M d, Y');
            }
            $noshows = implode('<br>', $noshowdates);
        }

        // qualified (next exercise is the course's last exercise) and tested status
        $qualified = $this->student->qualified();
        $requiresevaluation = $this->subscriber->requires_skills_evaluation();
        $endorsed = false;
        $endorsementmsg = '';
        $hasexams = count($this->student->get_quize_grades()) > 0;

        if ($requiresevaluation) {

            // endorsement information
            $endorsed = get_user_preferences('local_booking_' .$this->courseid . '_endorse', false, $studentid);
            $endorsementmgs = array();
            if ($endorsed) {
                $endorserid = get_user_preferences('local_booking_' . $this->courseid . '_endorser', '', $studentid);
                $endorser = !empty($endorserid) ? participant::get_fullname($endorserid) : get_string('notfound', 'local_booking');
                $endorseronts = !empty($endorserid) ? get_user_preferences('local_booking_' . $this->courseid . '_endorsedate', '', $studentid) : time();
                $endorsementmgs = [
                    'endorser' => $endorser,
                    'endorsedate' =>  (new \Datetime('@'.$endorseronts))->format('M j\, Y')
                ];
                $endorsementmsg = get_string($endorsed ? 'endorsementmgs' : 'skilltestendorse', 'local_booking', $endorsementmgs);
            }
        }

        // moodle profile url
        $moodleprofile = new moodle_url('/user/view.php', [
            'id' => $studentid,
            'course' => $this->courseid,
        ]);

        // Course activity section
        $lastlogindate = $this->student->get_last_login_date();
        $lastlogindate = !empty($lastlogindate) ? $lastlogindate->format('M j\, Y') : '';
        $lastgradeddate = $this->student->get_last_graded_date();
        $lastgradeddate = !empty($lastgradeddate) ? $lastgradeddate->format('M j\, Y') : '';

        // graduation status
        if ($this->student->graduated()) {

            $graduationstatus = get_string('graduated', 'local_booking') . ' ' .  $lastgradeddate;

        } elseif ($this->student->tested()) {

            $graduationstatus = get_string(($this->student->passed() ? 'checkpassed' : 'checkfailed'), 'local_booking') . ' ' .  $this->subscriber->get_graduation_exercise(true);

        } else {
            $graduationstatus = ($qualified ? get_string('qualified', 'local_booking') . ' ' .
                $this->subscriber->get_graduation_exercise(true) : get_string('notqualified', 'local_booking'));
        }

        // log in as url
        $loginas = new moodle_url('/course/loginas.php', [
            'id' => $this->courseid,
            'user' => $studentid,
            'sesskey' => sesskey(),
        ]);

        // student skill test recommendation letter
        $recommendationletterlink = new moodle_url('/local/booking/report.php', [
            'courseid' => $this->courseid,
            'userid' => $studentid,
            'report' => 'recommendation',
        ]);

        // student outline report
        $outlinereporturl = new moodle_url('/report/outline/user.php', [
            'id' => $studentid,
            'course' => $this->courseid,
            'mode' => 'outline',
        ]);

        // student complete report
        $completereporturl = new moodle_url('/report/outline/user.php', [
            'id' => $studentid,
            'course' => $this->courseid,
            'mode' => 'complete',
        ]);

        // student logbook
        $logbookurl = new moodle_url('/local/booking/logbook.php', [
            'courseid' => $this->courseid,
            'userid' => $studentid
        ]);

        // student mentor report
        $mentorreporturl = new moodle_url('/local/booking/report.php', [
            'courseid' => $this->courseid,
            'userid' => $studentid,
            'report' => 'mentor',
        ]);

        // student theory exam report
        $theoryexamreporturl = new moodle_url('/local/booking/report.php', [
            'courseid' => $this->courseid,
            'userid' => $studentid,
            'report' => 'theoryexam',
        ]);

        // student practical exam report
        $practicalexamreporturl = new moodle_url('/local/booking/report.php', [
            'courseid' => $this->courseid,
            'userid' => $studentid,
            'report' => 'practicalexam',
        ]);

        // session progression options and related exporter data
        $options = [
            'isinstructor' => true,
            'isexaminer'   => true,
            'viewtype'     => 'sessions',
            'readonly'     => true
        ];
        $related = [
            'context'       => \context_system::instance(),
            'coursemodules' => $this->subscriber->get_modules(),
            'course'        => $this->subscriber,
            'filter'        => 'active'
        ];

        $return = [
            'fullname'                 => $this->student->get_name(),
            'timezone'                 => $moodleuser->timezone == '99' ? $CFG->timezone : $moodleuser->timezone,
            'fleet'                    => $this->student->get_fleet() ?: get_string('none'),
            'sim1'                     => $this->student->get_simulator(),
            'sim2'                     => $this->student->get_simulator(false),
            'noshows'                  => $noshows,
            'moodleprofileurl'         => $moodleprofile->out(false),
            'recency'                  => $this->student->get_priority()->get_recency_days(),
            'courseactivity'           => $this->student->get_priority()->get_activity_count(false),
            'slots'                    => $this->student->get_priority()->get_slot_count(),
            'modulescompleted'         => get_string('modscompletemsg', 'local_booking', $modsinfo),
            'enroldate'                => $this->student->get_enrol_date()->format('M j\, Y'),
            'lastlogin'                => $lastlogindate,
            'lastgraded'               => $lastgradeddate,
            'lastlesson'               => $currentlesson,
            'lastlessoncompleted'      => $this->student->has_completed_lessons() ? get_string('yes') : get_string('no'),
            'graduationstatus'         => $graduationstatus,
            'qualified'                => $qualified,
            'requiresevaluation'       => $requiresevaluation,
            'endorsed'                 => $endorsed,
            'endorser'                 => $USER->id,
            'endorsername'             => \local_booking\local\participant\entities\participant::get_fullname($USER->id),
            'endorsementlocked'        => !empty($endorsed) && $endorsed && $endorserid != $USER->id,
            'endorsementmgs'           => $endorsementmsg,
            'recommendationletterlink' => $recommendationletterlink->out(false),
            'suspended'                => !$this->student->is_active(),
            'onholdrestrictionenabled' => $this->subscriber->onholdperiod != 0,
            'onhold'                   => student::is_member_of($this->courseid, $studentid, LOCAL_BOOKING_ONHOLDGROUP),
            'onholdgroup'              => LOCAL_BOOKING_ONHOLDGROUP,
            'keepactive'               => student::is_member_of($this->courseid, $studentid,LOCAL_BOOKING_KEEPACTIVEGROUP),
            'keepactivegroup'          => LOCAL_BOOKING_KEEPACTIVEGROUP,
            'waitrestrictionenabled'   => $this->subscriber->postingwait != 0,
            'restrictionoverride'      => get_user_preferences('local_booking_' . $this->courseid . '_availabilityoverride', false, $studentid),
            'admin'                    => has_capability('moodle/user:loginas', $this->related['context']),
            'hasexams'                 => $hasexams,
            'loginasurl'               => $loginas->out(false),
            'outlinereporturl'         => $outlinereporturl->out(false),
            'completereporturl'        => $completereporturl->out(false),
            'logbookurl'               => $logbookurl->out(false),
            'mentorreporturl'          => $mentorreporturl->out(false),
            'theoryexamreporturl'      => $theoryexamreporturl->out(false),
            'practicalexamreporturl'   => $practicalexamreporturl->out(false),
            'tested'                   => $this->student->tested(),
            'coursemodules'            => base_view::get_modules($output, $this->subscriber, $options),
            'sessions'                 => booking_student_exporter::get_sessions($output, $this->student, $related),
            'comment'                  => $this->student->get_comment(),
        ];

        return $return;
    }

    /**
     * Returns a list of objects that are related.
     *
     * @return array
     */
    protected static function define_related() {
        return array(
            'context' => 'context'
        );
    }
}
