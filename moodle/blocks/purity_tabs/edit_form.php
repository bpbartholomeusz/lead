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

class block_purity_tabs_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {

        // Set the tabs properly
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->tabs = 5;
        }
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_tabs'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_tabs'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_tabs'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_tabs'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Justify Tabs options.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_justify_tabs', get_string('justify_tabs', 'block_purity_tabs'), $options);
        $mform->setDefault('config_justify_tabs', '0');
        $mform->setType('config_justify_tabs', PARAM_RAW); // Not needed for select elements.

        // Animation options.
        $options = array(
            'none' => 'None',
            'fade' => 'Fade',
            'scale' => 'Scale',
            'slide-top' => 'Slide Top',
            'slide-bottom' => 'Slide Bottom',
            'slide-left' => 'Slide Left',
            'slide-right' => 'Slide Right',
            'slide-horizontal' => 'Slide Horizontal',
            'slide-vertical' => 'Slide Vertical',
        );
        $mform->addElement('select', 'config_animation', get_string('animation', 'block_purity_tabs'), $options);
        $mform->setDefault('config_animation', 'fade');
        $mform->setType('config_animation', PARAM_RAW); // Not needed for select elements.

        // Set the number of tabs
        $tabs_count = range(0, 15);
        $mform->addElement('select', 'config_tabs', get_string('tabs', 'block_purity_tabs'), $tabs_count);
        $mform->setDefault('config_tabs', $data->tabs);

        for($i = 1; $i <= $data->tabs; $i++) {

            $mform->addElement('header', 'config_header' . $i , 'Tab ' . $i);

            // Title
            $mform->addElement('text', 'config_tab_title' . $i, get_string('tab_title', 'block_purity_tabs', $i));
            $mform->setDefault('config_tab_title' .$i , 'Lorem');
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            // Text
            $mform->addElement('textarea', 'config_tab_text' . $i, get_string('tab_text', 'block_purity_tabs', $i), 'wrap="virtual" rows="5" cols="50"');
            $mform->setDefault('config_tab_text' . $i, '');
            $mform->setType('config_tab_text' . $i, PARAM_RAW);

            // CSS Class
            $mform->addElement('text', 'config_tab_css_class' . $i, get_string('tab_css_class', 'block_purity_tabs', $i));
            $mform->setDefault('config_tab_css_class' . $i, '');
            $mform->setType('config_tab_css_class' . $i, PARAM_RAW);

       }
 
    }
}