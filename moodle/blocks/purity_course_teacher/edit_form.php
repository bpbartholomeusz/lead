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

class block_purity_course_teacher_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_course_teacher'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_course_teacher'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_TEXT);

        // Select Teacher
        $users = $this->get_users();
        $mform->addElement('autocomplete', 'config_teacher', get_string('teacher', 'block_purity_course_teacher'), $users, [
            'multiple' => false,
            'ajax' => 'tool_lp/form-user-selector',
        ]);

        // Show Image options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_image', get_string('show_image', 'block_purity_course_teacher'), $options);
        $mform->setDefault('config_show_image', '1');
        $mform->setType('config_show_image', PARAM_RAW); // Not needed for select elements.

        // Show Courses Count options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_courses_count', get_string('show_courses_count', 'block_purity_course_teacher'), $options);
        $mform->setDefault('config_show_courses_count', '1');
        $mform->setType('config_show_courses_count', PARAM_RAW); // Not needed for select elements.

        // Show Student Count options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_students_count', get_string('show_students_count', 'block_purity_course_teacher'), $options);
        $mform->setDefault('config_show_students_count', '1');
        $mform->setType('config_show_students_count', PARAM_RAW); // Not needed for select elements.

        // Set a title.
        $mform->addElement('text', 'config_title', get_string('title', 'block_purity_course_teacher'));
        $mform->setDefault('config_title', '');
        $mform->setType('config_title', PARAM_TEXT);

        // Show description options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_description', get_string('show_description', 'block_purity_course_teacher'), $options);
        $mform->setDefault('config_show_description', '1');
        $mform->setType('config_show_description', PARAM_RAW); // Not needed for select elements.

        // Set description characters limit.
        $mform->addElement('text', 'config_description_limit', get_string('description_limit', 'block_purity_course_teacher'));
        $mform->setDefault('config_description_limit', '');
        $mform->setType('config_description_limit', PARAM_TEXT);

        // Social Links Target.
        $options = array(
            '_self' => 'Self',
            '_blank' => 'Blank',
            '_parent' => 'Parent',
        );
        $select = $mform->addElement('select', 'config_social_links_target', get_string('social_links_target', 'block_purity_course_teacher'), $options);
        $select->setSelected('_self');

        // Set Facebook URL.
        $mform->addElement('text', 'config_facebook_url', get_string('facebook_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_facebook_url', '');
        $mform->setType('config_facebook_url', PARAM_TEXT);

        // Set Twitter URL.
        $mform->addElement('text', 'config_twitter_url', get_string('twitter_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_twitter_url', '');
        $mform->setType('config_twitter_url', PARAM_TEXT);

        // Set LinkedIn URL.
        $mform->addElement('text', 'config_linkedin_url', get_string('linkedin_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_linkedin_url', '');
        $mform->setType('config_linkedin_url', PARAM_TEXT);

        // Set Instagram URL.
        $mform->addElement('text', 'config_instagram_url', get_string('instagram_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_instagram_url', '');
        $mform->setType('config_instagram_url', PARAM_TEXT);

        // Set YouTube URL.
        $mform->addElement('text', 'config_youtube_url', get_string('youtube_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_youtube_url', '');
        $mform->setType('config_youtube_url', PARAM_TEXT);

        // Set Github URL.
        $mform->addElement('text', 'config_github_url', get_string('github_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_github_url', '');
        $mform->setType('config_github_url', PARAM_TEXT);

        // Set Email.
        $mform->addElement('text', 'config_email', get_string('email', 'block_purity_course_teacher'));
        $mform->setDefault('config_email', '');
        $mform->setType('config_email', PARAM_TEXT);

        // Set Phone.
        $mform->addElement('text', 'config_phone', get_string('phone', 'block_purity_course_teacher'));
        $mform->setDefault('config_phone', '');
        $mform->setType('config_phone', PARAM_TEXT);

        // Set Website URL.
        $mform->addElement('text', 'config_website_url', get_string('website_url', 'block_purity_course_teacher'));
        $mform->setDefault('config_website_url', '');
        $mform->setType('config_website_url', PARAM_TEXT);
 
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