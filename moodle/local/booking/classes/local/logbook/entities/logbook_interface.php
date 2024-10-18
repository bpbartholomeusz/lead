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
 * Logbook interface
 *
 * @package    local_booking
 * @author     Mustafa Hajjar (mustafahajjar@gmail.com)
 * @copyright  BAVirtual.co.uk © 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_booking\local\logbook\entities;


defined('MOODLE_INTERNAL') || die();

/**
 * Interface for a log entry class.
 *
 * @copyright  BAVirtual.co.uk © 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface logbook_interface {

    /**
     * Loads the logbook of entries for a user.
     *
     * @param  bool $allentries whether to get entries for all courses
     * @return bool true if the Logbook has entries
     */
    public function load(bool $allentries = false);

    /**
     * Creates a logbook entry.
     *
     * @return logentry
     */
    public function create_logentry();

    /**
     * Save a logbook entry.
     *
     * @param logentry $logentry
     * @return int The id of the logbook entery inserted
     */
    public function insert(logentry $logentry);

    /**
     * Update a logbook entry.
     *
     * @param logentry $logentry
     * @return bool
     */
    public function update(logentry $logentry);

    /**
     * Deletes a logbook entry and its associated logentires.
     *
     * @param int $logentryid
     * @param bool $cascade
     * @return bool
     */
    public function delete(int $logentryid, $cascade);

    /**
     * Insert/Update then link the instructor
     * and student logbook entries.
     *
     * @param int $courseid
     * @param logentry $instructorlogentry
     * @param logentry $studentlogentry
     * @return bool
     */
    public static function save_linked_logentries(int $courseid, logentry $instructorlogentry, logentry $studentlogentry);

    /**
     * Load a logbook entry.
     *
     * @return logentry
     */
    public function get_logentry(int $logentryid);

    /**
     * get an entry from the logbook entris by
     * exercise id.
     *
     * @param int $exerciseid: The entry associated exercise id
     * @return logentry $logentry The logbook entry db record
     */
    public function get_logentry_by_exericseid(int $exerciseid);

    /**
     * get an entry from the logbook entris by
     * session id.
     *
     * @param int $sessionid: The entry associated session id
     * @return logentry $logentry The logbook entry db record
     */
    public function get_logentry_by_sessionid(int $sessionid);

    /**
     * Get the logbook entries time totals
     *
     * @param  bool $tostring   The totals in string time format
     * @param  bool $allcourses The totals of all courses
     * @param  int  $examid     The graduation exam exericse id
     * @return object           The logbook time table totals
     */
    public function get_summary(bool $tostring = false, bool $allcourses = false, int $examid = 0);

    /**
     * Get the logbook entries time totals until a specific exercise
     *
     * @param int       $section    The section number of the exercise up until.
     * @param  bool $tostring The totals in string time format
     * @return array          The logbook time table totals
     */
    public function get_summary_upto_exercise(int $section, bool $tostring = false);

    /**
     * Get the course id for the log entry.
     *
     * @return int
     */
    public function get_courseid();

    /**
     * Get the user user id of the log entry.
     *
     * @return int
     */
    public function get_userid();

    /**
     * Get the user name of the log entry.
     *
     * @return string
     */
    public function get_username();

    /**
     * Set the course  id for the log entry.
     *
     * @param int $courseid
     */
    public function set_courseid(int $courseid);

    /**
     * Set the studnet user id of the log entry.
     *
     * @param int $userid
     */
    public function set_userid(int $userid);

    /**
     * Whether the logbook as entries or not.
     *
     * @return bool true if the Logbook has entries
     */
    public function has_entries();
}
