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

class block_purity_course_highlights_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {

        // Set the block items properly
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->items = 0;
        }
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_course_highlights'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_course_highlights'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_TEXT);

        // Set the number of items
        $items_count = range(0, 15);
        $mform->addElement('select', 'config_items', get_string('items', 'block_purity_course_highlights'), $items_count);
        $mform->setDefault('config_items', $data->items);

        for($i = 1; $i <= $data->items; $i++) {

            $mform->addElement('header', 'config_header' . $i , 'Item ' . $i);

            // Icon.
            $mform->addElement('text', 'config_item_icon' . $i, get_string('item_icon', 'block_purity_course_highlights', $i));
            $mform->setDefault('config_item_icon' .$i , '<i class="fa fa-info-circle" aria-hidden="true"></i>');
            $mform->setType('config_item_icon' . $i, PARAM_RAW);

            // Text.
            $mform->addElement('text', 'config_item_text' . $i, get_string('item_text', 'block_purity_course_highlights', $i));
            $mform->setDefault('config_item_text' .$i , 'Lorem Ipsum');
            $mform->setType('config_item_text' . $i, PARAM_TEXT);

            // Value.
            $mform->addElement('text', 'config_item_value' . $i, get_string('item_value', 'block_purity_course_highlights', $i));
            $mform->setDefault('config_item_value' .$i , '');
            $mform->setType('config_item_value' . $i, PARAM_TEXT);

       }
 
    }
}