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
 * The block settings.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_course_enrolment_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_course_enrolment'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_course_enrolment'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_TEXT);

        // Show Price option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_price', get_string('show_price', 'block_purity_course_enrolment'), $options);
        $mform->setDefault('config_show_price', '0');
        $mform->setType('config_show_price', PARAM_RAW); // Not needed for select elements.

        // Price Currency Symbol.
        $mform->addElement('text', 'config_currency_symbol', get_string('currency_symbol', 'block_purity_course_enrolment'));
        $mform->setDefault('config_currency_symbol', '');
        $mform->setType('config_currency_symbol', PARAM_TEXT);

        // Price Accent Color option.
        $options = array(
            'primary' => 'Primary',
            'default' => 'Secondary',
            'info' => 'Info',
            'success' => 'Success',
            'danger' => 'Danger',
            'warning' => 'Warning',
        );
        $mform->addElement('select', 'config_price_accent_color', get_string('price_accent_color', 'block_purity_course_enrolment'), $options);
        $mform->setDefault('config_price_accent_color', 'primary');
        $mform->setType('config_price_accent_color', PARAM_RAW); // Not needed for select elements.

        // Select Teacher
        $users = $this->get_users();
        $mform->addElement('autocomplete', 'config_teacher', get_string('teacher', 'block_purity_course_enrolment'), $users, [
            'multiple' => false,
            'ajax' => 'tool_lp/form-user-selector',
        ]);
 
    }

    private function get_users() {
        global $DB;

        $usernames = [];

        if(empty($this->block->config->teacher)) return [];
        $ids = $this->block->config->teacher;

        list($uids, $params) = $DB->get_in_or_equal($ids);
        $rs = $DB->get_recordset_select('user', 'id ' . $uids, $params, '', 'id,firstname,lastname,email');

        foreach ($rs as $record)
        {
          $usernames[$record->id] = $record->firstname . ' ' . $record->lastname . ' ' . $record->email;
        }
        $rs->close();

        return $usernames;
    }
}