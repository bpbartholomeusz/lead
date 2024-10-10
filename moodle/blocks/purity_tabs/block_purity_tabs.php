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
 * Purity Tabs block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_tabs extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_tabs', 'block_purity_tabs');
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
			$this->config->custom_title = 'Our Core Values';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->tabs = '5';
			$this->config->tab_title1 = 'Lorem';
			$this->config->tab_text1 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->tab_title2 = 'Ipsum';
			$this->config->tab_text2 = 'ivo Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->tab_title3 = 'Dolor';
			$this->config->tab_text3 = 'bivo Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->tab_title4 = 'Cursu';
			$this->config->tab_text4 = 'divo Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->tab_title5 = 'Morbi';
			$this->config->tab_text5 = ' mivo Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_tabs', 'block_purity_tabs');            
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
            $data->tabs = is_numeric($data->tabs) ? (int)$data->tabs : 5;
        } else {
            $data = new stdClass();
            $data->tabs = 0;
        }

        // Set Defaults
        if (!isset($this->config->animation)) { $this->config->animation = 'fade'; }
	 
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
		$tab_nav_items = '';
		$tab_content_items = '';
	    if ($data->tabs > 0) {
	    	for ($i = 1; $i <= $data->tabs; $i++) {
				$tab_title = 'tab_title' . $i;
				$tab_text = 'tab_text' . $i;
				$tab_css_class = 'tab_css_class' . $i;

				// Title
				if ($data->$tab_title) {
					$title = '<li class="tab-nav-item ' . $data->$tab_css_class . '"><a href="#">' . format_text($data->$tab_title, FORMAT_HTML, array('filter' => true)) . '</a></li>';
				} else {
					$title = '';
				}

				// Text
				if ($data->$tab_text) {
					$text = '<li class="tab-content-item ' . $data->$tab_css_class . '">' . format_text($data->$tab_text, FORMAT_HTML, array('filter' => true)) . '</li>';
				} else {
					$text = '';
				}

				// Tab Nav Items
				$tab_nav_items .= $title;

				// Tab Content Items
				$tab_content_items .= $text;
	    	}

	    }

		// Justify Tabs
		if ($this->config->justify_tabs) {
			$justify_tabs_class = 'uk-child-width-expand';
		} else {
			$justify_tabs_class = '';
		}

		// Animation
		if ($this->config->animation == 'none') {
			$animation = '';
		} else if ($this->config->animation == 'fade') {
			$animation = 'animation: uk-animation-fade';
		} else if ($this->config->animation == 'scale') {
			$animation = 'animation: uk-animation-scale-up';
		} else if ($this->config->animation == 'slide-top') {
			$animation = 'animation: uk-animation-slide-top';
		} else if ($this->config->animation == 'slide-bottom') {
			$animation = 'animation: uk-animation-slide-bottom';
		} else if ($this->config->animation == 'slide-left') {
			$animation = 'animation: uk-animation-slide-left';
		} else if ($this->config->animation == 'slide-right') {
			$animation = 'animation: uk-animation-slide-right';
		} else if ($this->config->animation == 'slide-horizontal') {
			$animation = 'animation: uk-animation-slide-left, uk-animation-slide-right';
		} else if ($this->config->animation == 'slide-vertical') {
			$animation = 'animation: uk-animation-slide-top, uk-animation-slide-bottom';
		}

	    $this->content->text = '
	    	<div class="tabs-container">
	    		' . $block_intro . '
	    		<div class="tabs-inner-wrapper">
		    		<ul class="uk-tab ' . $justify_tabs_class . '" uk-tab="' . $animation . '">
		    			' . $tab_nav_items . '
		    		</ul>
		    		<ul class="uk-switcher">
		    			' . $tab_content_items . '
		    		</ul>
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
