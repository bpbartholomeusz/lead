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

class block_purity_features_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {

        // Set the items properly
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->items = 3;
        }
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_features'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_features'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_features'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_features'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Items per row option.
        $options = array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        );
        $mform->addElement('select', 'config_items_per_row', get_string('items_per_row', 'block_purity_features'), $options);
        $mform->setDefault('config_item_per_row', '3');
        $mform->setType('config_item_per_row', PARAM_RAW); // Not needed for select elements.

        // Set the number of items
        $items_count = range(0, 15);
        $mform->addElement('select', 'config_items', get_string('items', 'block_purity_features'), $items_count);
        $mform->setDefault('config_items', $data->items);

        for($i = 1; $i <= $data->items; $i++) {

            $mform->addElement('header', 'config_header' . $i , 'Item ' . $i);

            // Accent.
            $options = array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'info' => 'Info',
                'success' => 'Success',
                'danger' => 'Danger',
                'warning' => 'Warning',
            );
            $select = $mform->addElement('select', 'config_item_accent' . $i, get_string('item_accent', 'block_purity_features'), $options);
            $select->setSelected('primary');

            // Icon.
            $mform->addElement('text', 'config_item_icon' . $i, get_string('item_icon', 'block_purity_features', $i));
            $mform->setDefault('config_item_icon' .$i , '<i class="fa fa-info-circle" aria-hidden="true"></i>');
            $mform->setType('config_item_icon' . $i, PARAM_RAW);

            // Title
            $mform->addElement('text', 'config_item_title' . $i, get_string('item_title', 'block_purity_features', $i));
            $mform->setDefault('config_item_title' .$i , 'Lorem Ipsum');
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            // Text.
            $mform->addElement('textarea', 'config_item_text' . $i, get_string('item_text', 'block_purity_features', $i), 'wrap="virtual" rows="5" cols="50"');
            $mform->setDefault('config_item_text' . $i, '');
            $mform->setType('config_item_text' . $i, PARAM_RAW);

            // Button Text.
            $mform->addElement('text', 'config_item_button_text' . $i, get_string('item_button_text', 'block_purity_features', $i));
            $mform->setDefault('config_item_button_text' . $i, '');
            $mform->setType('config_item_button_text' . $i, PARAM_RAW);

            // Button Link.
            $mform->addElement('text', 'config_item_button_link' . $i, get_string('item_button_link', 'block_purity_features', $i));
            $mform->setDefault('config_item_button_link' . $i, '');
            $mform->setType('config_item_button_link' . $i, PARAM_RAW);

            // Button Target.
            $options = array(
                '_self' => 'Self',
                '_blank' => 'Blank',
                '_parent' => 'Parent',
            );
            $select = $mform->addElement('select', 'config_item_button_target' . $i, get_string('item_button_target', 'block_purity_features', $i), $options);
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
            $select = $mform->addElement('select', 'config_item_button_style' . $i, get_string('item_button_style', 'block_purity_features', $i), $options);
            $select->setSelected('btn-primary');

            // CSS Class.
            $mform->addElement('text', 'config_item_css_class' . $i, get_string('item_css_class', 'block_purity_features', $i));
            $mform->setDefault('config_item_css_class' . $i, '');
            $mform->setType('config_item_css_class' . $i, PARAM_RAW);

       }
 
    }
}