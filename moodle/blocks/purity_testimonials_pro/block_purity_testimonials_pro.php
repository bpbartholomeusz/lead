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
 * Purity Testimonials PRO block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_testimonials_pro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_testimonials_pro', 'block_purity_testimonials_pro');
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
			$this->config->custom_title = 'What Our Customers Say';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->behaviour = 'slider';
			$this->config->items_per_row = '3';
			$this->config->style = '0';
			$this->config->testimonials = '3';
			$this->config->testimonial_text1 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis, porttitor eget turpis lobortis nunc quis metus dolor.';
			$this->config->testimonial_person_name1 = 'Peter Stevenson';
			$this->config->testimonial_person_position1 = 'Designer and CEO';
			$this->config->testimonial_text2 = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euis convallis sagittis quis euismod.';
			$this->config->testimonial_person_name2 = 'Alicia Patrick';
			$this->config->testimonial_person_position2 = 'Public Relations';
			$this->config->testimonial_text3 = 'Vivamus id mi non quam congue venenatis et at lorem. Ut ullamcorper odio id metus eleif tincidunt. Proin ante arcu, aliquam nec rhon sit amet, consequat vitae suscipit.';
			$this->config->testimonial_person_name3 = 'Thomas Jones';
			$this->config->testimonial_person_position3 = 'Marketing and Sales';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_testimonials_pro', 'block_purity_testimonials_pro');            
	        } else {
	            $this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
	        }
	    }
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
		global $CFG;

	    if ($this->content !== null) {
	      return $this->content;
	    }

	    // Set items
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->testimonials = is_numeric($data->testimonials) ? (int)$data->testimonials : 3;
        } else {
            $data = new stdClass();
            $data->testimonials = 0;
        }

		// Set defaults
		if (!isset($this->config->autoplay)) { $this->config->autoplay = 'true'; }
		if (!isset($this->config->autoplay_interval)) { $this->config->autoplay_interval = '6000'; }
		if (!isset($this->config->pause_hover)) { $this->config->pause_hover = 'true'; }
	 
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
	    if ($data->testimonials > 0) {
	    	$fs = get_file_storage();
	    	for ($i = 1; $i <= $data->testimonials; $i++) {
				$testimonial_text = 'testimonial_text' . $i;
				$testimonial_person_image = 'testimonial_person_image' . $i;
				$testimonial_person_name = 'testimonial_person_name' . $i;
				$testimonial_person_position = 'testimonial_person_position' . $i;
				$testimonial_css_class = 'testimonial_css_class' . $i;

				// Text
				if ($data->$testimonial_text) {
					$text = '<div class="testimonial-text">' . format_text($data->$testimonial_text, FORMAT_HTML, array('filter' => true)) . '</div>';
				} else {
					$text = '';
				}

				// Image URL
				$files = $fs->get_area_files($this->context->id, 'block_purity_testimonials_pro', 'testimonials', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
				if (!empty($data->$testimonial_person_image) && count($files) >= 1) {
					$mainfile = reset($files);
					$mainfile = $mainfile->get_filename();
					$data->$testimonial_person_image = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_purity_testimonials_pro/testimonials/" . $i . '/' . $mainfile);
				} else {
					$data->$testimonial_person_image = '';
				}

				// Image
				if ($data->$testimonial_person_image) {
					$image = '
						<div class="testimonial-image-container">
							<img src="' . $data->$testimonial_person_image . '" alt="' . format_text($data->$testimonial_person_name, FORMAT_HTML, array('filter' => true)) . '">
						</div>';
				} else {
					$image = '';
				}

				// Name
				if ($data->$testimonial_person_name) {
					$name = '<div class="testimonial-person-name">' . format_text($data->$testimonial_person_name, FORMAT_HTML, array('filter' => true)) . '</div>';
				} else {
					$name = '';
				}

				// Position
				if ($data->$testimonial_person_position) {
					$position = '<div class="testimonial-person-position">' . format_text($data->$testimonial_person_position, FORMAT_HTML, array('filter' => true)) . '</div>';
				} else {
					$position = '';
				}

				// Person Info
				if ($image || $name || $position) {
					$person_info = '
						<div class="testimonial-person-info">
							' . $image . '
							<div class="testimonial-person-name-position">
								' . $name . '
								' . $position . '
							</div>
						</div>';
				} else {
					$person_info = '';
				}

				// Item
				if ($this->config->style == '0') {
					$style_item = '
						<div class="card ' . $data->$testimonial_css_class . '">
							<div class="card-body">
								' . $text . '
								' . $person_info . '
							</div>
						</div>';
				} else if ($this->config->style == '1') {
					$style_item = '
						<div class="testimonial-style2-wrapper ' . $data->$testimonial_css_class . '">
							<div class="card">
								<div class="card-body">
									' . $text . '
								</div>
							</div>
							' . $person_info . '
						</div>';
				} else if ($this->config->style == '2') {
					$style_item = '
						<div class="testimonial-style3-wrapper ' . $data->$testimonial_css_class . '">
							' . $text . '
							' . $person_info . '
						</div>';
				}

				// Testimonial Items
				if ($this->config->behaviour == 'slider') {
					$items .= '<li>' . $style_item . '</li>';
				} else {
					$items .= '<div class="uk-width-1-' . $this->config->items_per_row . '@m">' . $style_item . '</div>';
				}

	    	}
	    }

	    // Slider Options
	    $slider_options = '
	    	autoplay: ' . $this->config->autoplay . ';
	    	autoplay-interval: ' . $this->config->autoplay_interval . ';
	    	pause-on-hover: ' . $this->config->pause_hover . ';
	    ';
	    $navigation = $this->config->navigation == 'arrows' || 'both' ? 'uk-visible-toggle-custom' : '';

	    // Navigation Arrows
	    if ($this->config->navigation == 'none' || $this->config->navigation == 'dots') {
	    	$arrows = '';
	    } else {
	    	$arrows = '
	    		<a class="uk-position-center-left uk-position-medium uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
			    <a class="uk-position-center-right uk-position-medium uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
	    	';
	    }

	    // Navigation Dots
	    if ($this->config->navigation == 'none' || $this->config->navigation == 'arrows') {
	    	$dots = '';
	    } else {
	    	$dots = '<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>';
	    }

	    // Final Items
	    if ($this->config->behaviour == 'slider') {
	    	$final_items = '
	    		<div class="uk-slider-container-offset" uk-slider="' . $slider_options . '">
	    			<div class="uk-position-relative ' . $navigation . '" tabindex="-1">
			    		<ul class="uk-slider-items uk-child-width-1-2@s uk-child-width-1-' . $this->config->items_per_row . '@m uk-grid uk-grid-medium">
			    			' . $items . '
			    		</ul>

			    		' . $arrows . '
			    	</div>

			    	' . $dots . '
	    		</div>';
	    } else {
	    	$final_items = '
	    		<div class="uk-grid-medium" uk-grid>
	    			' . $items . '
	    		</div>';
	    }


	    $this->content->text = '
	    	<div class="testimonials-pro-container">
	    		' . $block_intro . '
	    		' . $final_items . '
	    	</div>';

	    // $this->content->footer = '';
	 
	    return $this->content;
	}

    /**
     * Store block instance data.
     */
    function instance_config_save($data, $nolongerused = false) {
        global $CFG;

        for($i = 1; $i <= $data->testimonials; $i++) {
            $field = 'testimonial_person_image' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            file_save_draft_area_files($data->$field, $this->context->id, 'block_purity_testimonials_pro', 'testimonials', $i,
            	array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.svg') ));
        }

        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Delete block instance data.
     */
    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_purity_testimonials_pro');
        return true;
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
		} else if ($this->config->style == '2') {
			$attributes['class'] .= ' style3';
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
