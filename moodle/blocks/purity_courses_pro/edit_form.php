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

class block_purity_courses_pro_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_courses_pro'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_courses_pro'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Behaviour options.
        $options = array(
            'static' => 'Static',
            'slider' => 'Slider',
        );
        $mform->addElement('select', 'config_behaviour', get_string('behaviour', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_item_per_row', 'slider');
        $mform->setType('config_item_per_row', PARAM_RAW); // Not needed for select elements.

        // Items per row option.
        $options = array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        );
        $mform->addElement('select', 'config_items_per_row', get_string('items_per_row', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_items_per_row', '3');
        $mform->setType('config_items_per_row', PARAM_RAW); // Not needed for select elements.

        // Navigation options.
        $options = array(
            'arrows' => 'Arrows',
            'dots' => 'Dots',
            'both' => 'Both',
            'none' => 'None',
        );
        $mform->addElement('select', 'config_navigation', get_string('navigation', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_navigation', 'arrows');
        $mform->setType('config_navigation', PARAM_RAW); // Not needed for select elements.

        // Autoplay options.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_autoplay', get_string('autoplay', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_autoplay', 'true');
        $mform->setType('config_autoplay', PARAM_RAW); // Not needed for select elements.

        // Autoplay Interval.
        $mform->addElement('text', 'config_autoplay_interval', get_string('autoplay_interval', 'block_purity_courses_pro'));
        $mform->setDefault('config_autoplay_interval', '6000');
        $mform->setType('config_autoplay_interval', PARAM_RAW);

        // Pause on Hover.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_pause_hover', get_string('pause_hover', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_pause_hover', 'true');
        $mform->setType('config_pause_hover', PARAM_RAW); // Not needed for select elements.

        // Style options.
        $options = array(
            '0' => 'Style 1',
            '1' => 'Style 2',
        );
        $mform->addElement('select', 'config_style', get_string('style', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_style', '0');
        $mform->setType('config_style', PARAM_RAW); // Not needed for select elements.

        // Show Image options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_image', get_string('show_image', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_image', '1');
        $mform->setType('config_show_image', PARAM_RAW); // Not needed for select elements.

        // Set Image height.
        $mform->addElement('text', 'config_image_height', get_string('image_height', 'block_purity_courses_pro'));
        $mform->setDefault('config_image_height', '200px');
        $mform->setType('config_image_height', PARAM_TEXT);

        // Show Title options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Full Name',
            '2' => 'Show Short Name',
        );
        $mform->addElement('select', 'config_show_title', get_string('show_title', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_title', '1');
        $mform->setType('config_show_title', PARAM_RAW); // Not needed for select elements.

        // Set Title characters limit.
        $mform->addElement('text', 'config_title_limit', get_string('title_limit', 'block_purity_courses_pro'));
        $mform->setDefault('config_title_limit', '32');
        $mform->setType('config_title_limit', PARAM_TEXT);

        // Show Summary options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_summary', get_string('show_summary', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_summary', '1');
        $mform->setType('config_show_summary', PARAM_RAW); // Not needed for select elements.

        // Set Summary characters limit.
        $mform->addElement('text', 'config_summary_limit', get_string('summary_limit', 'block_purity_courses_pro'));
        $mform->setDefault('config_summary_limit', '100');
        $mform->setType('config_summary_limit', PARAM_TEXT);

        // Show Teacher options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_teacher', get_string('show_teacher', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_teacher', '0');
        $mform->setType('config_show_teacher', PARAM_RAW); // Not needed for select elements.

        // Show Date option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show Created Date',
            '2' => 'Show Last Modified Date',
            '3' => 'Show Start-End Date',
        );
        $mform->addElement('select', 'config_show_date', get_string('show_date', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_date', '2');
        $mform->setType('config_show_date', PARAM_RAW); // Not needed for select elements.

        // Show Category options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_category', get_string('show_category', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_category', '1');
        $mform->setType('config_show_category', PARAM_RAW); // Not needed for select elements.

        // Show Enrolments options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_enrolments', get_string('show_enrolments', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_enrolments', '1');
        $mform->setType('config_show_enrolments', PARAM_RAW); // Not needed for select elements.

        // Show Price option.
        $options = array(
            'hide' => 'Hide',
            'show-top' => 'Show Top',
            'show-bottom' => 'Show Bottom',
        );
        $mform->addElement('select', 'config_show_price', get_string('show_price', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_show_price', '0');
        $mform->setType('config_show_price', PARAM_RAW); // Not needed for select elements.

        // Price Currency Symbol.
        $mform->addElement('text', 'config_currency_symbol', get_string('currency_symbol', 'block_purity_courses_pro'));
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
        $mform->addElement('select', 'config_price_accent_color', get_string('price_accent_color', 'block_purity_courses_pro'), $options);
        $mform->setDefault('config_price_accent_color', 'primary');
        $mform->setType('config_price_accent_color', PARAM_RAW); // Not needed for select elements.

        // Button Text.
        $mform->addElement('text', 'config_button_text', get_string('button_text', 'block_purity_courses_pro'));
        $mform->setDefault('config_button_text', '');
        $mform->setType('config_button_text', PARAM_RAW);

        // Button Link.
        $mform->addElement('text', 'config_button_link', get_string('button_link', 'block_purity_courses_pro'));
        $mform->setDefault('config_button_link', '');
        $mform->setType('config_button_link', PARAM_RAW);

        // Button Target.
        $options = array(
            '_self' => 'Self',
            '_blank' => 'Blank',
            '_parent' => 'Parent',
        );
        $select = $mform->addElement('select', 'config_button_target', get_string('button_target', 'block_purity_courses_pro'), $options);
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
        $select = $mform->addElement('select', 'config_button_style', get_string('button_style', 'block_purity_courses_pro'), $options);
        $select->setSelected('btn-primary');

        // Courses
        $options = array(
            'multiple' => true,
            'noselectionstring' => get_string('select_courses', 'block_purity_courses_pro'),
        );
        $mform->addElement('course', 'config_courses', get_string('courses'), $options);
        $mform->addRule('config_courses', get_string('required'), 'required', null, 'client', false, false);
 
    }
}