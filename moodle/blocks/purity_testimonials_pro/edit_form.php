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

class block_purity_testimonials_pro_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        global $CFG;

        // Set the testimonials properly
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->testimonials = 3;
        }
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header option.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_testimonials_pro'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_testimonials_pro'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Behaviour options.
        $options = array(
            'static' => 'Static',
            'slider' => 'Slider',
        );
        $mform->addElement('select', 'config_behaviour', get_string('behaviour', 'block_purity_testimonials_pro'), $options);
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
        $mform->addElement('select', 'config_items_per_row', get_string('items_per_row', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_items_per_row', '3');
        $mform->setType('config_items_per_row', PARAM_RAW); // Not needed for select elements.

        // Navigation options.
        $options = array(
            'arrows' => 'Arrows',
            'dots' => 'Dots',
            'both' => 'Both',
            'none' => 'None',
        );
        $mform->addElement('select', 'config_navigation', get_string('navigation', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_navigation', 'arrows');
        $mform->setType('config_navigation', PARAM_RAW); // Not needed for select elements.

        // Autoplay options.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_autoplay', get_string('autoplay', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_autoplay', 'true');
        $mform->setType('config_autoplay', PARAM_RAW); // Not needed for select elements.

        // Autoplay Interval.
        $mform->addElement('text', 'config_autoplay_interval', get_string('autoplay_interval', 'block_purity_testimonials_pro'));
        $mform->setDefault('config_autoplay_interval', '6000');
        $mform->setType('config_autoplay_interval', PARAM_RAW);

        // Pause on Hover.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_pause_hover', get_string('pause_hover', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_pause_hover', 'true');
        $mform->setType('config_pause_hover', PARAM_RAW); // Not needed for select elements.

        // Style options.
        $options = array(
            '0' => 'Style 1',
            '1' => 'Style 2',
            '2' => 'Style 3',
        );
        $mform->addElement('select', 'config_style', get_string('style', 'block_purity_testimonials_pro'), $options);
        $mform->setDefault('config_style', '0');
        $mform->setType('config_style', PARAM_RAW); // Not needed for select elements.

        // Set the number of testimonials
        $testimonials_count = range(0, 15);
        $mform->addElement('select', 'config_testimonials', get_string('testimonials', 'block_purity_testimonials_pro'), $testimonials_count);
        $mform->setDefault('config_testimonials', $data->testimonials);

        for($i = 1; $i <= $data->testimonials; $i++) {

            $mform->addElement('header', 'config_header' . $i , 'Testimonial ' . $i);

            // Text
            $mform->addElement('textarea', 'config_testimonial_text' . $i, get_string('testimonial_text', 'block_purity_testimonials_pro', $i), 'wrap="virtual" rows="5" cols="50"');
            $mform->setDefault('config_testimonial_text' . $i, '');
            $mform->setType('config_testimonial_text' . $i, PARAM_RAW);

            // Image
            $mform->addElement('filemanager', 'config_testimonial_person_image' . $i, get_string('testimonial_person_image', 'block_purity_testimonials_pro', $i), null,
                    array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                    'accepted_types' => array('.png', '.jpg', '.gif', '.svg') ));

            // Name
            $mform->addElement('text', 'config_testimonial_person_name' . $i, get_string('testimonial_person_name', 'block_purity_testimonials_pro', $i));
            $mform->setDefault('config_testimonial_person_name' .$i , 'John Doe');
            $mform->setType('config_testimonial_person_name' . $i, PARAM_TEXT);

            // Position
            $mform->addElement('text', 'config_testimonial_person_position' . $i, get_string('testimonial_person_position', 'block_purity_testimonials_pro', $i));
            $mform->setDefault('config_testimonial_person_position' .$i , 'Front-End Developer');
            $mform->setType('config_testimonial_person_position' . $i, PARAM_TEXT);

            // CSS Class.
            $mform->addElement('text', 'config_testimonial_css_class' . $i, get_string('testimonial_css_class', 'block_purity_testimonials_pro', $i));
            $mform->setDefault('config_testimonial_css_class' . $i, '');
            $mform->setType('config_testimonial_css_class' . $i, PARAM_RAW);
        }
 
    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {

            for($i = 1; $i <= $this->block->config->testimonials; $i++) {
                $field = 'testimonial_person_image' . $i;
                $configuration_field = 'config_testimonial_person_image' . $i;
                $draftitemid = file_get_submitted_draft_itemid($configuration_field);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_purity_testimonials_pro', 'testimonials', $i, array('subdirs'=>false));
                $defaults->$configuration_field['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
            }
        }

        parent::set_data($defaults);
    }
}