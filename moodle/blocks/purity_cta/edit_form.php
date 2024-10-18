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

class block_purity_cta_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_cta'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_cta'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_cta'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_cta'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Style options.
        $options = array(
            '0' => 'Style 1',
            '1' => 'Style 2',
        );
        $mform->addElement('select', 'config_style', get_string('style', 'block_purity_cta'), $options);
        $mform->setDefault('config_style', '0');
        $mform->setType('config_style', PARAM_RAW); // Not needed for select elements.

        // Orientation options.
        $options = array(
            'vertical' => 'Vertical',
            'horizontal' => 'Horizontal',
        );
        $mform->addElement('select', 'config_orientation', get_string('orientation', 'block_purity_cta'), $options);
        $mform->setDefault('config_orientation', '0');
        $mform->setType('config_orientation', PARAM_RAW); // Not needed for select elements.

        // Title.
        $mform->addElement('text', 'config_title', get_string('title', 'block_purity_cta'));
        $mform->setDefault('config_title', 'Become A Teacher Today!');
        $mform->setType('config_title', PARAM_TEXT);

        // Text.
        $mform->addElement('textarea', 'config_text', get_string('text', 'block_purity_cta'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_text', 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.');
        $mform->setType('config_text', PARAM_RAW);

        // Button Text.
        $mform->addElement('text', 'config_button_text', get_string('button_text', 'block_purity_cta'));
        $mform->setDefault('config_button_text', 'Register Now');
        $mform->setType('config_button_text', PARAM_RAW);

        // Button Link.
        $mform->addElement('text', 'config_button_link', get_string('button_link', 'block_purity_cta'));
        $mform->setDefault('config_button_link', '#');
        $mform->setType('config_button_link', PARAM_RAW);

        // Button Target.
        $options = array(
            '_self' => 'Self',
            '_blank' => 'Blank',
            '_parent' => 'Parent',
        );
        $select = $mform->addElement('select', 'config_button_target', get_string('button_target', 'block_purity_cta'), $options);
        $select->setSelected('_self');

        // Button Style.
        $options = array(
            'btn-primary' => 'Standard (Primary)',
            'btn-secondary' => 'Standard (Secondary)',
            'btn-white' => 'Standard (White)',
            'btn-info' => 'Standard (Info)',
            'btn-success' => 'Standard (Success)',
            'btn-danger' => 'Standard (Danger)',
            'btn-warning' => 'Standard (Warning)',
            'btn-outline-primary' => 'Outline (Primary)',
            'btn-outline-secondry' => 'Outline (Secondary)',
            'btn-outline-white' => 'Outline (White)',
            'btn-outline-info' => 'Outline (Info)',
            'btn-outline-success' => 'Outline (Success)',
            'btn-outline-danger' => 'Outline (Danger)',
            'btn-outline-warning' => 'Outline (Warning)',
        );
        $select = $mform->addElement('select', 'config_button_style', get_string('button_style', 'block_purity_cta'), $options);
        $select->setSelected('btn-primary');
 
    }
}