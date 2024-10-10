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

class block_purity_course_intro_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        global $CFG;
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Header options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_course_intro'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_TEXT);

        // Style options.
        $options = array(
            '0' => 'Style 1',
            '1' => 'Style 2',
        );
        $mform->addElement('select', 'config_style', get_string('style', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_style', '0');
        $mform->setType('config_style', PARAM_RAW); // Not needed for select elements.

        // Show Media options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Default Course Image',
            '2' => 'Show Custom Image',
            '3' => 'Show Embeded Video',
        );
        $mform->addElement('select', 'config_show_media', get_string('show_media', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_media', '1');
        $mform->setType('config_show_media', PARAM_RAW); // Not needed for select elements.

        // Set media height.
        $mform->addElement('text', 'config_media_height', get_string('media_height', 'block_purity_course_intro'));
        $mform->setDefault('config_media_height', '450px');
        $mform->setType('config_media_height', PARAM_TEXT);

        // Custom Image
        $mform->addElement('filemanager', 'config_custom_image', get_string('custom_image', 'block_purity_course_intro'), null,
                array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.svg') ));

        // Set video URL.
        $mform->addElement('text', 'config_video_url', get_string('video_url', 'block_purity_course_intro'));
        $mform->setDefault('config_video_url', '');
        $mform->setType('config_video_url', PARAM_RAW);

        // Show Category options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_category', get_string('show_category', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_category', '1');
        $mform->setType('config_show_category', PARAM_RAW); // Not needed for select elements.

        // Show Name options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Full Name',
            '2' => 'Show Short Name',
        );
        $mform->addElement('select', 'config_show_name', get_string('show_name', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_name', '1');
        $mform->setType('config_show_name', PARAM_RAW); // Not needed for select elements.

        // Show Teacher options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_teacher', get_string('show_teacher', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_teacher', '0');
        $mform->setType('config_show_teacher', PARAM_RAW); // Not needed for select elements.

        // Select Teacher
        $users = $this->get_users();
        $mform->addElement('autocomplete', 'config_teacher', get_string('teacher', 'block_purity_course_intro'), $users, [
            'multiple' => false,
            'ajax' => 'tool_lp/form-user-selector',
        ]);


        // Show Date option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Created Date',
            '2' => 'Show Last Modified Date',
            '3' => 'Show Start-End Date',
        );
        $mform->addElement('select', 'config_show_date', get_string('show_date', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_date', '2');
        $mform->setType('config_show_date', PARAM_RAW); // Not needed for select elements.

        // Show Enrolments options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_enrolments', get_string('show_enrolments', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_enrolments', '1');
        $mform->setType('config_show_enrolments', PARAM_RAW); // Not needed for select elements.

        // Show Summary options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Default Course Summary',
            '2' => 'Show Custom Summary',
        );
        $mform->addElement('select', 'config_show_summary', get_string('show_summary', 'block_purity_course_intro'), $options);
        $mform->setDefault('config_show_summary', '1');
        $mform->setType('config_show_summary', PARAM_RAW); // Not needed for select elements.

        // Custom Summary
        $mform->addElement('editor', 'config_custom_summary', get_string('custom_summary', 'block_purity_course_intro'));
        $mform->setDefault('config_custom_summary', '');
        $mform->setType('config_custom_summary', PARAM_RAW);
 
    }

    public function set_data($defaults) {
    
        if (empty($entry->id)) {
            $entry = new stdClass;
            $entry->id = null;
        }

        $draftitemid = file_get_submitted_draft_itemid('config_custom_image');

        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_purity_course_intro', 'content', 0,
                    array('subdirs'=>true));

        $entry->attachments = $draftitemid;

        parent::set_data($defaults);        

        if ($data = parent::get_data()) {

            file_save_draft_area_files($data->config_custom_image, $this->block->context->id, 'block_purity_course_intro', 'content', 0, 
                array('subdirs' => true));
        }

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