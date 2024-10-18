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
 * Purity Features block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_features extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_features', 'block_purity_features');
    }

    /**
     * Overrides block instance content.
     * Called immediately after init().
     */
	public function specialization() {

		// Set defaults
		if (empty($this->config)) {
			$this->config = new \stdClass();
			$this->config->show_as_card = '0';
			$this->config->show_header = '1';
			$this->config->custom_title = 'Follow Your Dreams';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->items_per_row = '3';
			$this->config->items = '3';
			$this->config->item_accent1 = 'primary';
			$this->config->item_icon1 = '<i class="fa fa-check" aria-hidden="true"></i>';
			$this->config->item_title1 = 'DOWNLOAD PURITY';
			$this->config->item_text1 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->item_button_text1 = 'Learn More';
			$this->config->item_button_link1 = '#';
			$this->config->item_button_target1 = '_self';
			$this->config->item_button_style1 = 'btn-primary';
			$this->config->item_accent2 = 'success';
			$this->config->item_icon2 = '<i class="fa fa-check" aria-hidden="true"></i>';
			$this->config->item_title2 = 'BUILD SOMETHING';
			$this->config->item_text2 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->item_button_text2 = 'Learn More';
			$this->config->item_button_link2 = '#';
			$this->config->item_button_target2 = '_self';
			$this->config->item_button_style2 = 'btn-success';
			$this->config->item_accent3 = 'warning';
			$this->config->item_icon3 = '<i class="fa fa-check" aria-hidden="true"></i>';
			$this->config->item_title3 = 'PREPARE LUNCH';
			$this->config->item_text3 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->item_button_text3 = 'Learn More';
			$this->config->item_button_link3 = '#';
			$this->config->item_button_target3 = '_self';
			$this->config->item_button_style3 = 'btn-warning';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_features', 'block_purity_features');            
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
            $data->items = is_numeric($data->items) ? (int)$data->items : 3;
        } else {
            $data = new stdClass();
            $data->items = 0;
        }
	 
	    $this->content = new stdClass;

	    // Block Intro
	    if ($this->config->show_header && $this->config->show_as_card == '0') {
	    	$block_intro = '
	    		<div class="purity-block-intro">
	    			<h3 class="block-main-title">' . $this->title . '</h3>';

	    	if ($this->config->custom_subtitle) {
	    		$block_intro .= '
	    			<div class="block-title-separator"></div>
	    			<div class="block-subtitle">' . format_text($this->config->custom_subtitle, FORMAT_HTML, array('filter' => true)) . '</div>';
	    	}

	    	$block_intro .= '</div>';
	    } else {
	    	$block_intro = '';
	    }

	    // Items
	    $items = '';
	    if ($data->items > 0) {
	    	for ($i = 1; $i <= $data->items; $i++) {
	    		$item_accent = 'item_accent' . $i;
	    		$item_icon = 'item_icon' . $i;
	    		$item_title = 'item_title' . $i;
	    		$item_text = 'item_text' . $i;
	    		$item_button_text = 'item_button_text' . $i;
	    		$item_button_link = 'item_button_link' . $i;
	    		$item_button_target = 'item_button_target' . $i;
	    		$item_button_style = 'item_button_style' . $i;
	    		$item_css_class = 'item_css_class' . $i;

	            // Icon
	            if ($data->$item_icon) {
	            	$icon = '<div class="feature-icon icon-round icon-' . $data->$item_accent . '">' . format_text($data->$item_icon, FORMAT_HTML, array('filter' => true)) . '</div>';
	            } else {
	            	$icon = '';
	            }

	            // Title
	            if ($data->$item_title) {
	            	$title = '<h3 class="feature-title text-' . $data->$item_accent . '">' . format_text($data->$item_title, FORMAT_HTML, array('filter' => true)) . '</h3>';
	            } else {
	            	$title = '';
	            }

	            // Text
	            if ($data->$item_text) {
	            	$text = '<div class="feature-text">' . format_text($data->$item_text, FORMAT_HTML, array('filter' => true)) . '</div>';
	            } else {
	            	$text = '';
	            }

		        // Button
	            if ($data->$item_button_text) {
	            	$button = '
	            		<div class="feature-button">
	            			<a class="btn ' . $data->$item_button_style . '" href="' . $data->$item_button_link . '" target="' . $data->$item_button_target . '">' . format_text($data->$item_button_text, FORMAT_HTML, array('filter' => true)) . '</a>
	            		</div>';
	            } else {
	            	$button = '';
	            }

	            // Feature Item
              $declared_item_css_class = isset($data->$item_css_class) ? $data->$item_css_class : '';
	            $items .= '
	            	<div class="uk-width-1-' . $this->config->items_per_row . '@m">
		            	<div class="card card-lift-hover ' . $declared_item_css_class . '">
		            		<div class="card-body">
		            			' . $icon . '
		            			' . $title . '
		            			' . $text . '
		            			' . $button . '
		            		</div>
		            	</div>
	            	</div>';
	    	}

	    }

	    $this->content->text = '
	    	<div class="features-container">
	    		' . $block_intro . '
	    		<div class="uk-grid-medium" uk-grid>
	    			' . $items . '
	    		</div>
	    	</div>';

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

		if ($this->config->show_as_card == '0') {
			$attributes['class'] .= ' block_not_card';
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
		return array('all' => true);
	}

}
