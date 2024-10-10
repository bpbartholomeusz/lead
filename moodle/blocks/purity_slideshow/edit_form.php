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

class block_purity_slideshow_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        global $CFG;

        // Set the slides properly
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $data = $this->block->config;
        } else {
            $data = new stdClass();
            $data->slides = 2;
        }
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Show Block as Card.
        $options = array(
            '0' => 'No',
            '1' => 'Yes',
        );
        $mform->addElement('select', 'config_show_as_card', get_string('show_as_card', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_show_as_card', '0');
        $mform->setType('config_show_as_card', PARAM_RAW); // Not needed for select elements.

        // Display Header options.
        $options = array(
            '0' => 'Hide',
            '1' => 'Show',
        );
        $mform->addElement('select', 'config_show_header', get_string('show_header', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_show_header', '1');
        $mform->setType('config_show_header', PARAM_RAW); // Not needed for select elements.

        // Set a custom title.
        $mform->addElement('text', 'config_custom_title', get_string('custom_title', 'block_purity_slideshow'));
        $mform->setDefault('config_custom_title', '');
        $mform->setType('config_custom_title', PARAM_RAW);

        // Set a custom subtitle.
        $mform->addElement('textarea', 'config_custom_subtitle', get_string('custom_subtitle', 'block_purity_slideshow'), 'wrap="virtual" rows="5" cols="50"');
        $mform->setDefault('config_custom_subtitle', '');
        $mform->setType('config_custom_subtitle', PARAM_RAW);

        // Height.
        $mform->addElement('text', 'config_height', get_string('height', 'block_purity_slideshow'));
        $mform->setDefault('config_height', '500');
        $mform->setType('config_height', PARAM_RAW);

        // Navigation options.
        $options = array(
            'arrows' => 'Arrows (Show on Hover)',
            'arrows_always' => 'Arrows (Always Visible)',
            'none' => 'None',
        );
        $mform->addElement('select', 'config_navigation', get_string('navigation', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_navigation', 'arrows');
        $mform->setType('config_navigation', PARAM_RAW); // Not needed for select elements.

        // Animation options.
        $options = array(
            'slide' => 'Slide',
            'fade' => 'Fade',
            'scale' => 'Scale',
            'pull' => 'Pull',
            'push' => 'Push',
        );
        $mform->addElement('select', 'config_animation', get_string('animation', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_animation', 'slide');
        $mform->setType('config_animation', PARAM_RAW); // Not needed for select elements.

        // Autoplay options.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_autoplay', get_string('autoplay', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_autoplay', 'true');
        $mform->setType('config_autoplay', PARAM_RAW); // Not needed for select elements.

        // Autoplay Interval.
        $mform->addElement('text', 'config_autoplay_interval', get_string('autoplay_interval', 'block_purity_slideshow'));
        $mform->setDefault('config_autoplay_interval', '6000');
        $mform->setType('config_autoplay_interval', PARAM_RAW);

        // Pause on Hover.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_pause_hover', get_string('pause_hover', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_pause_hover', 'true');
        $mform->setType('config_pause_hover', PARAM_RAW); // Not needed for select elements.

        // Fullscreen options.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_fullscreen', get_string('fullscreen', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_fullscreen', 'false');
        $mform->setType('config_fullscreen', PARAM_RAW); // Not needed for select elements.

        // Ken Burns options.
        $options = array(
            'true' => 'Enabled',
            'false' => 'Disabled',
        );
        $mform->addElement('select', 'config_kenburns', get_string('kenburns', 'block_purity_slideshow'), $options);
        $mform->setDefault('config_kenburns', 'false');
        $mform->setType('config_kenburns', PARAM_RAW); // Not needed for select elements.

        // Set the number of slides
        $slides_count = range(0, 15);
        $mform->addElement('select', 'config_slides', get_string('slides', 'block_purity_slideshow'), $slides_count);
        $mform->setDefault('config_slideshow', $data->slides);

        for($i = 1; $i <= $data->slides; $i++) {

            $mform->addElement('header', 'config_header' . $i , 'Slide ' . $i);

            // Slide Image
            $mform->addElement('filemanager', 'config_image_slide' . $i, get_string('slide_image', 'block_purity_slideshow', $i), null,
                    array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                    'accepted_types' => array('.png', '.jpg', '.gif', '.svg') ));

            // Image Alt Tag.
            $mform->addElement('text', 'config_image_alt' . $i, get_string('image_alt', 'block_purity_slideshow', $i));
            $mform->setDefault('config_image_alt' . $i, '');
            $mform->setType('config_image_alt' . $i, PARAM_RAW);

            // Title.
            $mform->addElement('text', 'config_title' . $i, get_string('title', 'block_purity_slideshow', $i));
            $mform->setDefault('config_title' . $i, '');
            $mform->setType('config_title' . $i, PARAM_RAW);

            // Text.
            $mform->addElement('textarea', 'config_text' . $i, get_string('text', 'block_purity_slideshow', $i), 'wrap="virtual" rows="5" cols="50"');
            $mform->setDefault('config_text' . $i, '');
            $mform->setType('config_text' . $i, PARAM_RAW);

            // Button 1 Text.
            $mform->addElement('text', 'config_button1_text' . $i, get_string('button1_text', 'block_purity_slideshow', $i));
            $mform->setDefault('config_button1_text' . $i, '');
            $mform->setType('config_button1_text' . $i, PARAM_RAW);

            // Button 1 Link.
            $mform->addElement('text', 'config_button1_link' . $i, get_string('button1_link', 'block_purity_slideshow', $i));
            $mform->setDefault('config_button1_link' . $i, '');
            $mform->setType('config_button1_link' . $i, PARAM_RAW);

            // Button 1 Target.
            $options = array(
                '_self' => 'Self',
                '_blank' => 'Blank',
                '_parent' => 'Parent',
            );
            $select = $mform->addElement('select', 'config_button1_target' . $i, get_string('button1_target', 'block_purity_slideshow', $i), $options);
            $select->setSelected('_self');

            // Button 1 Style.
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
            $select = $mform->addElement('select', 'config_button1_style' . $i, get_string('button1_style', 'block_purity_slideshow', $i), $options);
            $select->setSelected('btn-primary');

            // Button 2 Text.
            $mform->addElement('text', 'config_button2_text' . $i, get_string('button2_text', 'block_purity_slideshow', $i));
            $mform->setDefault('config_button2_text' . $i, '');
            $mform->setType('config_button2_text' . $i, PARAM_RAW);

            // Button 2 Link.
            $mform->addElement('text', 'config_button2_link' . $i, get_string('button2_link', 'block_purity_slideshow', $i));
            $mform->setDefault('config_button2_link' . $i, '');
            $mform->setType('config_button2_link' . $i, PARAM_RAW);

            // Button 2 Target.
            $options = array(
                '_self' => 'Self',
                '_blank' => 'Blank',
                '_parent' => 'Parent',
            );
            $select = $mform->addElement('select', 'config_button2_target' . $i, get_string('button2_target', 'block_purity_slideshow', $i), $options);
            $select->setSelected('_self');

            // Button 2 Style.
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
            $select = $mform->addElement('select', 'config_button2_style' . $i, get_string('button2_style', 'block_purity_slideshow', $i), $options);
            $select->setSelected('btn-primary');

            // Overlay Container.
            $options = array(
                '0' => 'Disabled',
                '1' => 'Enabled',
            );
            $select = $mform->addElement('select', 'config_overlay_container' . $i, get_string('overlay_container', 'block_purity_slideshow'), $options);
            $select->setSelected('0');

            // Overlay Position
            $options = array(
                'top' => 'Top',
                'left' => 'Left',
                'right' => 'Right',
                'bottom' => 'Bottom',
                'center' => 'Center',
                'top-left' => 'Top Left',
                'top-center' => 'Top Center',
                'top-right' => 'Top Right',
                'center-left' => 'Center Left',
                'center-right' => 'Center Right',
                'bottom-left' => 'Bottom Left',
                'bottom-center' => 'Bottom Center',
                'bottom-right' => 'Bottom Right',
            );
            $select = $mform->addElement('select', 'config_overlay_position' . $i, get_string('overlay_position', 'block_purity_slideshow'), $options);
            $select->setSelected('center');

            // Overlay Style.
            $options = array(
                'uk-overlay-transparent-light' => 'Transparent (Light Text)',
                'uk-overlay-transparent-dark' => 'Transparent (Dark Text)',
                'uk-overlay-default' => 'Light',
                'uk-overlay-primary' => 'Dark',
            );
            $select = $mform->addElement('select', 'config_overlay_style' . $i, get_string('overlay_style', 'block_purity_slideshow'), $options);
            $select->setSelected('uk-overlay-default');

            // Overlay Animation.
            $options = array(
                'uk-transition-fade' => 'Fade',
                'uk-transition-scale-down' => 'Scale Down',
                'uk-transition-slide-top' => 'Slide Top',
                'uk-transition-slide-bottom' => 'Slide Bottom',
                'uk-transition-slide-left' => 'Slide Left',
                'uk-transition-slide-right' => 'Slide Right',
                'uk-transition-slide-top-small' => 'Slide Top Small',
                'uk-transition-slide-bottom-small' => 'Slide Bottom Small',
                'uk-transition-slide-left-small' => 'Slide Left Small',
                'uk-transition-slide-right-small' => 'Slide Right Small',
                'uk-transition-slide-top-medium' => 'Slide Top Medium',
                'uk-transition-slide-bottom-medium' => 'Slide Bottom Medium',
                'uk-transition-slide-left-medium' => 'Slide Left Medium',
                'uk-transition-slide-right-medium' => 'Slide Right Medium',
            );
            $select = $mform->addElement('select', 'config_overlay_animation' . $i, get_string('overlay_animation', 'block_purity_slideshow'), $options);
            $select->setSelected('uk-transition-slide-bottom-small');

            // Overlay Width.
            $options = array(
                'auto' => 'Auto',
                '1' => '100%',
                '2' => '50%',
                '3' => '33.3%',
                '4' => '25%',
                '5' => '20%',
                '6' => '16.6%',
            );
            $select = $mform->addElement('select', 'config_overlay_width' . $i, get_string('overlay_width', 'block_purity_slideshow'), $options);
            $select->setSelected('auto');

            // CSS Class.
            $mform->addElement('text', 'config_css_class' . $i, get_string('css_class', 'block_purity_slideshow', $i));
            $mform->setDefault('config_css_class' . $i, '');
            $mform->setType('config_css_class' . $i, PARAM_RAW);

       }
 
    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {

            for($i = 1; $i <= $this->block->config->slides; $i++) {
                $field = 'image_slide' . $i;
                $configuration_field = 'config_image_slide' . $i;
                $draftitemid = file_get_submitted_draft_itemid($configuration_field);
                file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_purity_slideshow', 'slides', $i, array('subdirs'=>false));
                $defaults->$configuration_field['itemid'] = $draftitemid;
                $this->block->config->$field = $draftitemid;
            }
        }

        parent::set_data($defaults);
    }
}