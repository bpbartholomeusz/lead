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
 * Purity CTA block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_cta extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_cta', 'block_purity_cta');
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
			$this->config->show_header = '0';
			$this->config->custom_title = '';
			$this->config->custom_subtitle = '';
			$this->config->style = '0';
			$this->config->orientation = 'vertical';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_cta', 'block_purity_cta');            
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

		// Set defaults
		if (!isset($this->config->title)) { $this->config->title = 'Become A Teacher Today!'; }
		if (!isset($this->config->text)) { $this->config->text = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.'; }
		if (!isset($this->config->button_text)) { $this->config->button_text = 'Register Now'; }
		if (!isset($this->config->button_link)) { $this->config->button_link = '#'; }
		if (!isset($this->config->button_target)) { $this->config->button_target = '_self'; }
		if (!isset($this->config->button_style)) { $this->config->button_style = 'btn-primary'; }
	 
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

		// Title
		if ($this->config->title) {
			$title = '<h3 class="cta-title">' . format_text($this->config->title, FORMAT_HTML, array('filter' => true)) . '</h3>';
		} else {
			$title = '';
		}

		// Text
		if ($this->config->text) {
			$text = '<div class="cta-text">' . format_text($this->config->text, FORMAT_HTML, array('filter' => true)) . '</div>';
		} else {
			$text = '';
		}

		// Button
		if ($this->config->button_text) {
			$button = '
				<div class="button-container">
					<a class="btn ' . $this->config->button_style . '" href="' . $this->config->button_link . '" target="' . $this->config->button_target . '">' . format_text($this->config->button_text, FORMAT_HTML, array('filter' => true)) . '</a>
				</div>';
		} else {
			$button = '';
		}

		$cta_packed = '
			<div class="cta-title-text-container">
				' . $title . '
				' . $text . '
			</div>
			' . $button . '';

		// Style
		if ($this->config->style == '0') {
			$cta_wrapper = '
				<div class="card">
					<div class="card-body">
						' . $cta_packed . '
					</div>
				</div>';
		} else if ($this->config->style == '1') {
			$cta_wrapper = $cta_packed;
		}

	    $this->content->text = '
	    	<div class="cta-container">
	    		' . $block_intro . '
	    		' . $cta_wrapper . '
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

		if ($this->config->style == '0') {
			$attributes['class'] .= ' style1';
		} else if ($this->config->style == '1') {
			$attributes['class'] .= ' style2';
		}

		$attributes['class'] .= ' orientation-' . $this->config->orientation;

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
