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
 * Purity Slideshow block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_slideshow extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_slideshow', 'block_purity_slideshow');
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
			$this->config->show_as_card = '0';
			$this->config->custom_title = '';
			$this->config->custom_subtitle = '';
			$this->config->slides = '2';
			$this->config->title1 = 'Lorem Ipsum Dolor Sit Amet';
			$this->config->text1 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->button1_text1 = 'Find Our More';
			$this->config->button1_link1 = '#';
			$this->config->button1_target1 = '_self';
			$this->config->button1_style1 = 'btn-primary';
			$this->config->overlay_style1 = 'uk-overlay-default';
			$this->config->overlay_position1 = 'center';
			$this->config->overlay_animation1 = 'uk-transition-slide-bottom-small';
			$this->config->overlay_width1 = '3';
			$this->config->overlay_container1 = '0';
			$this->config->title2 = 'Maecenas Mauris Orci Pellentesque';
			$this->config->text2 = 'Lorem ipsum dolor sit amet, conse adipiscing elit. Maecenas mauris orci, pellentesque at vestibulum quis.';
			$this->config->button1_text2 = 'Find Our More';
			$this->config->button1_link2 = '#';
			$this->config->button1_target2 = '_self';
			$this->config->button1_style2 = 'btn-primary';
			$this->config->overlay_style2 = 'uk-overlay-default';
			$this->config->overlay_position2 = 'center';
			$this->config->overlay_animation2 = 'uk-transition-slide-bottom-small';
			$this->config->overlay_width2 = '3';
			$this->config->overlay_container2 = '0';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_slideshow', 'block_purity_slideshow');            
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
            $data->slides = is_numeric($data->slides) ? (int)$data->slides : 2;
        } else {
            $data = new stdClass();
            $data->slides = 0;
        }

	    // Set defaults
	    if (!isset($this->config->height)) { $this->config->height = '500'; }
	    if (!isset($this->config->navigation)) { $this->config->navigation = 'arrows'; }
	    if (!isset($this->config->animation)) { $this->config->animation = 'slide'; }
	    if (!isset($this->config->autoplay)) { $this->config->autoplay = 'true'; }
	    if (!isset($this->config->autoplay_interval)) { $this->config->autoplay_interval = '6000'; }
	    if (!isset($this->config->pause_hover)) { $this->config->pause_hover = 'true'; }
	    if (!isset($this->config->fullscreen)) { $this->config->fullscreen = 'false'; }
	    if (!isset($this->config->kenburns)) { $this->config->kenburns = 'false'; }
	 
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

	    // Slides
	    $slides = '';
	    if ($data->slides > 0) {
	    	$fs = get_file_storage();
	    	for ($i = 1; $i <= $data->slides; $i++) {
				$slide_image = 'image_slide' . $i;
				$slide_image_alt = 'image_alt' . $i;
				$slide_title = 'title' . $i;
				$slide_text = 'text' . $i;
				$slide_button1_text = 'button1_text' . $i;
				$slide_button1_link = 'button1_link' . $i;
				$slide_button1_target = 'button1_target' . $i;
				$slide_button1_style = 'button1_style' . $i;
				$slide_button2_text = 'button2_text' . $i;
				$slide_button2_link = 'button2_link' . $i;
				$slide_button2_target = 'button2_target' . $i;
				$slide_button2_style = 'button2_style' . $i;
				$slide_overlay_container = 'overlay_container' . $i;
				$slide_overlay_position = 'overlay_position' . $i;
				$slide_overlay_style = 'overlay_style' . $i;
				$slide_overlay_animation = 'overlay_animation' . $i;
				$slide_overlay_width = 'overlay_width' . $i;
				$slide_css_class = 'css_class' . $i;

				// Image URL
				$files = $fs->get_area_files($this->context->id, 'block_purity_slideshow', 'slides', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
				if (!empty($data->$slide_image) && count($files) >= 1) {
					$mainfile = reset($files);
					$mainfile = $mainfile->get_filename();
					$data->$slide_image = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_purity_slideshow/slides/" . $i . '/' . $mainfile);
				} else {
					$data->$slide_image = $CFG->wwwroot .'/theme/purity/pix/slideshow1.jpg';
				}

				// Image
				if ($this->config->kenburns == 'true') {
					$image = '
						<div class="uk-position-cover uk-animation-kenburns">
							<img src="' . $data->$slide_image . '" alt="' . format_text($data->$slide_image_alt, FORMAT_HTML, array('filter' => true)) . '" uk-cover>
						</div>';
				} else {
					$image = '<img src="' . $data->$slide_image . '" alt="' . format_text($data->$slide_image_alt, FORMAT_HTML, array('filter' => true)) . '" uk-cover>';
				}

	            // Title
	            if ($data->$slide_title) {
	            	$title = '<h3 class="slide-title">' . format_text($data->$slide_title, FORMAT_HTML, array('filter' => true)) . '</h3>';
	            } else {
	            	$title = '';
	            }

	            // Text
	            if ($data->$slide_text) {
	            	$text = '<div class="slide-text">' . format_text($data->$slide_text, FORMAT_HTML, array('filter' => true)) . '</div>';
	            } else {
	            	$text = '';
	            }

	            // Button 1
	            if ($data->$slide_button1_text) {
	            	$button1 = '<a class="btn ' . $data->$slide_button1_style . '" href="' . $data->$slide_button1_link . '" target="' . $data->$slide_button1_target . '">' . format_text($data->$slide_button1_text, FORMAT_HTML, array('filter' => true)) . '</a>';
	            } else {
	            	$button1 = '';
	            }

	            // Button 2
	            if ($data->$slide_button2_text) {
	            	$button2 = '<a class="btn ' . $data->$slide_button2_style . '" href="' . $data->$slide_button2_link . '" target="' . $data->$slide_button2_target . '">' . format_text($data->$slide_button2_text, FORMAT_HTML, array('filter' => true)) . '</a>';
	            } else {
	            	$button2 = '';
	            }

	            // Buttons Container
	            if ($button1 || $button2) {
	            	$buttons_container = '
	            		<div class="slide-buttons-container">
	            			' . $button1 . '
	            			' . $button2 . '
	            		</div>';
	            } else {
	            	$buttons_container = '';
	            }

	            // Overlay
	            if ($title || $text || $buttons_container) {
	            	if ($data->$slide_overlay_position == 'top') {
	            		$overlay_position_class = 'uk-position-top';
	            	} else if ($data->$slide_overlay_position == 'left') {
	            		$overlay_position_class = 'uk-position-left';
	            	} else if ($data->$slide_overlay_position == 'right') {
	            		$overlay_position_class = 'uk-position-right';
	            	} else if ($data->$slide_overlay_position == 'bottom') {
	            		$overlay_position_class = 'uk-position-bottom';
	            	} else {
	            		$overlay_position_class = 'uk-position-medium';
	            	}

	            	if ($data->$slide_overlay_position == 'center') {
	            		$slide_position_classes = 'uk-flex uk-flex-middle uk-flex-center text-center';
	            	} else if ($data->$slide_overlay_position == 'top-left') {
	            		$slide_position_classes = 'uk-flex uk-flex-top';
	            	} else if ($data->$slide_overlay_position == 'top-center') {
	            		$slide_position_classes = 'uk-flex uk-flex-top uk-flex-center';
	            	} else if ($data->$slide_overlay_position == 'top-right') {
	            		$slide_position_classes = 'uk-flex uk-flex-top uk-flex-right';
	            	} else if ($data->$slide_overlay_position == 'center-left') {
	            		$slide_position_classes = 'uk-flex uk-flex-middle';
	            	} else if ($data->$slide_overlay_position == 'center-right') {
	            		$slide_position_classes = 'uk-flex uk-flex-middle uk-flex-right';
	            	} else if ($data->$slide_overlay_position == 'bottom-left') {
	            		$slide_position_classes = 'uk-flex uk-flex-bottom';
	            	} else if ($data->$slide_overlay_position == 'bottom-center') {
	            		$slide_position_classes = 'uk-flex uk-flex-bottom uk-flex-center';
	            	} else if ($data->$slide_overlay_position == 'bottom-right') {
	            		$slide_position_classes = 'uk-flex uk-flex-bottom uk-flex-right';
	            	} else {
	            		$slide_position_classes = '';
	            	}

	            	if ($data->$slide_overlay_container == '1'
	            		&& $data->$slide_overlay_position != 'top'
	            		&& $data->$slide_overlay_position != 'left'
	            		&& $data->$slide_overlay_position != 'right'
	            		&& $data->$slide_overlay_position != 'bottom'
	            		) {
	            		$container_class = 'container';
	            	} else {
	            		$container_class = '';
	            	}

	            	$overlay = '
	            		<div class="overlay-container ' . $container_class . ' ' . $slide_position_classes . '">
		            		<div class="uk-overlay ' . $overlay_position_class . ' ' . $data->$slide_overlay_style . ' ' . $data->$slide_overlay_animation . ' uk-width-1-' . $data->$slide_overlay_width . '">
		            			' . $title . '
		            			' . $text . '
		            			' . $buttons_container . '
		            		</div>
	            		</div>';
	            } else {
	            	$overlay = '';
	            }

	            $slides .= '
	            	<li class="' . $data->$slide_css_class . '">
	            		' . $image . '
	            		' . $overlay . '
	            	</li>';
	    	}
	    }

	    // Slideshow Options
	    $slideshow_options = '
	    	animation: ' . $this->config->animation . ';
	    	autoplay: ' . $this->config->autoplay . ';
	    	autoplay-interval: ' . $this->config->autoplay_interval . ';
	    	pause-on-hover: ' . $this->config->pause_hover . ';
	    	min-height: ' . $this->config->height . ';
	    	max-height: ' . $this->config->height . ';
	    ';
	    $navigation = $this->config->navigation == 'arrows' ? 'uk-visible-toggle-custom' : '';
	    $fullscreen = $this->config->fullscreen == 'true' ? 'uk-height-viewport' : '';

	    if ($this->config->navigation == 'none') {
	    	$arrows = '';
	    } else {
	    	$arrows = '
	    		<a class="uk-position-center-left uk-position-medium uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
			    <a class="uk-position-center-right uk-position-medium uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
	    	';
	    }

	    $slideshow = '
			<div class="uk-position-relative ' . $navigation . '" tabindex="-1" uk-slideshow="' . $slideshow_options . '">

			    <ul class="uk-slideshow-items" ' . $fullscreen . '>
			    	' . $slides . '
			    </ul>

			    ' . $arrows . '

			</div>';

	    $this->content->text = '
	    	<div class="slideshow-container">
	    		' . $block_intro . '
	    		' . $slideshow . '
	    	</div>';

	    // $this->content->footer = '';
	 
	    return $this->content;
	}

    /**
     * Store block instance data.
     */
    function instance_config_save($data, $nolongerused = false) {
        global $CFG;

        for($i = 1; $i <= $data->slides; $i++) {
            $field = 'image_slide' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            file_save_draft_area_files($data->$field, $this->context->id, 'block_purity_slideshow', 'slides', $i,
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
        $fs->delete_area_files($this->context->id, 'block_purity_slideshow');
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
