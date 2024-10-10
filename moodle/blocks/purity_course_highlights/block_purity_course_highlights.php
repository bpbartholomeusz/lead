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
 * Purity Course Highlights block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_course_highlights extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_course_highlights', 'block_purity_course_highlights');
    }

    /**
     * Overrides block instance content.
     * Called immediately after init().
     */
	public function specialization() {

		// Set defaults
		if (empty($this->config)) {
			$this->config = new \stdClass();
			$this->config->show_header = '1';
			$this->config->custom_title = '';
			$this->config->items = '3';
			$this->config->item_icon1 = '<i class="fa fa-info-circle" aria-hidden="true"></i>';
			$this->config->item_text1 = 'Lorem Ipsum';
			$this->config->item_icon2 = '<i class="fa fa-info-circle" aria-hidden="true"></i>';
			$this->config->item_text2 = 'Lorem Ipsum';
			$this->config->item_icon3 = '<i class="fa fa-info-circle" aria-hidden="true"></i>';
			$this->config->item_text3 = 'Lorem Ipsum';
		}

		if (isset($this->config)) {
			if (empty($this->config->custom_title)) {
				$this->title = get_string('purity_course_highlights', 'block_purity_course_highlights');            
			} else {
				$this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
			}
		}
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
	    if ($this->content !== null) {
	      return $this->content;
	    }

	    // Set items
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->items = is_numeric($data->items) ? (int)$data->items : 0;
        } else {
            $data = new stdClass();
            $data->items = 0;
        }
	 
	    $this->content = new stdClass;

		// Check if items exist
		if ($data->items == 0) {
			$this->content->text = get_string('no_items_created', 'block_purity_course_highlights');
			return $this->content;
		}

		$highlights = '<ul class="course-highlights">';

        for ($i = 1; $i <= $data->items; $i++) {
        	$item_icon = 'item_icon' . $i;
			$item_text = 'item_text' . $i;
			$item_value = 'item_value' . $i;

			if ($data->$item_icon) {
				$item_icon_o = '<span class="item-icon">' . format_text($data->$item_icon, FORMAT_HTML, array('filter' => true)) . '</span>';
			} else {
				$item_icon_o = '';
			}

			if ($data->$item_text) {
				$item_text_o = format_text($data->$item_text, FORMAT_HTML, array('filter' => true));
			} else {
				$item_text_o = '';
			}

			if ($data->$item_value) {
				$item_value_o = '<div class="value-wrapper">' . format_text($data->$item_value, FORMAT_HTML, array('filter' => true)) . '</div>';
			} else {
				$item_value_o = '';
			}

			$highlights .='
				<li class="course-highlights-item">
					<div class="icon-text-wrapper">
						' . $item_icon_o . '
						' . $item_text_o . '
					</div>
					' . $item_value_o . '
				</li>';
        }

        $highlights .= '</ul>';

        $this->content->text = $highlights;

	    // $this->content->footer = '';
	 
	    return $this->content;
	}

    /**
     * Modifies the HTML attributes of the block.
     */
	public function html_attributes() {
	    $attributes = parent::html_attributes(); // Get default values

		if ($this->config->show_header == '0') {
		    $attributes['class'] .= ' block_hide_header';
		}  

	    return $attributes;
	}

    /**
     * Allows multiple instances of the block.
     */
	public function instance_allow_multiple() {
		return true;
	}

    /**
     * Enables block global configuration.
     */
    public function has_config() {
        return false;
    }

    /**
     * Locations where the block can be displayed.
     */
	public function applicable_formats() {
		return array('course-view' => true, 'mod-forum-view' => true);
	}

}
