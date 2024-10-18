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
 * Purity Teachers PRO block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_teachers_pro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_teachers_pro', 'block_purity_teachers_pro');
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
			$this->config->custom_title = 'Our Awesome Teachers';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->behaviour = 'slider';
			$this->config->items_per_row = '3';
			$this->config->style = '0';
			$this->config->image_height = '200px';
			$this->config->social_links_target = '_self';
			$this->config->teachers = '3';
			$this->config->teacher_name1 = 'Peter Stevenson';
			$this->config->teacher_position1 = 'Designer and CEO';
			$this->config->teacher_text1 = '';
			$this->config->teacher_name2 = 'Alicia Patrick';
			$this->config->teacher_position2 = 'Public Relations';
			$this->config->teacher_text2 = '';
			$this->config->teacher_name3 = 'Thomas Jones';
			$this->config->teacher_position3 = 'Marketing and Sales';
			$this->config->teacher_text3 = '';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_teachers_pro', 'block_purity_teachers_pro');            
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
            $data->teachers = is_numeric($data->teachers) ? (int)$data->teachers : 3;
        } else {
            $data = new stdClass();
            $data->teachers = 0;
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
	    if ($data->teachers > 0) {
	    	$fs = get_file_storage();
	    	for ($i = 1; $i <= $data->teachers; $i++) {
				$teacher_image = 'image_teacher' . $i;
	    		$teacher_name = 'teacher_name' . $i;
	    		$teacher_position = 'teacher_position' . $i;
	    		$teacher_text = 'teacher_text' . $i;
	    		$teacher_facebook_url = 'facebook_url' . $i;
	    		$teacher_twitter_url = 'twitter_url' . $i;
	    		$teacher_linkedin_url = 'linkedin_url' . $i;
	    		$teacher_instagram_url = 'instagram_url' . $i;
	    		$teacher_youtube_url = 'youtube_url' . $i;
	    		$teacher_github_url = 'github_url' . $i;
	    		$teacher_email = 'email' . $i;
	    		$teacher_phone = 'phone' . $i;
	    		$teacher_website_url = 'website_url' . $i;
	    		$teacher_css_class = 'teacher_css_class' . $i;

				// Image URL
				$files = $fs->get_area_files($this->context->id, 'block_purity_teachers_pro', 'teachers', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
				if (!empty($data->$teacher_image) && count($files) >= 1) {
					$mainfile = reset($files);
					$mainfile = $mainfile->get_filename();
					$data->$teacher_image = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_purity_teachers_pro/teachers/" . $i . '/' . $mainfile);
				} else {
					$data->$teacher_image = '';
				}

				// Image
				if ($data->$teacher_image) {
					$image = '<div class="teacher-image card-img-top" style="background-image: url(' . $data->$teacher_image . '); height: ' . $this->config->image_height . ';"></div>';
				} else {
					$image = '';
				}

				// Name
				if ($data->$teacher_name) {
					$name = '<h3 class="teacher-name">' . format_text($data->$teacher_name, FORMAT_HTML, array('filter' => true)) . '</h3>';
				} else {
					$name = '';
				}

				// Position
				if ($data->$teacher_position) {
					$position = '<div class="teacher-position">' . format_text($data->$teacher_position, FORMAT_HTML, array('filter' => true)) . '</div>';
				} else {
					$position = '';
				}

				// Text
				if ($data->$teacher_text) {
					$text = '<div class="teacher-text">' . format_text($data->$teacher_text, FORMAT_HTML, array('filter' => true)) . '</div>';
				} else {
					$text = '';
				}

				// Facebook
				if (!$data->$teacher_facebook_url) {
					$facebook = '';
				} else {
					$facebook = '
						<a href="' . $data->$teacher_facebook_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-facebook fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Twitter
				if (!$data->$teacher_twitter_url) {
					$twitter = '';
				} else {
					$twitter = '
						<a href="' . $data->$teacher_twitter_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-twitter fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// LinkedIn
				if (!$data->$teacher_linkedin_url) {
					$linkedin = '';
				} else {
					$linkedin = '
						<a href="' . $data->$teacher_linkedin_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-linkedin fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Instagram
				if (!$data->$teacher_instagram_url) {
					$instagram = '';
				} else {
					$instagram = '
						<a href="' . $data->$teacher_instagram_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-instagram fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Youtube
				if (!$data->$teacher_youtube_url) {
					$youtube = '';
				} else {
					$youtube = '
						<a href="' . $data->$teacher_youtube_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-youtube fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Github
				if (!$data->$teacher_github_url) {
					$github = '';
				} else {
					$github = '
						<a href="' . $data->$teacher_github_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-github fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Email
				if (!$data->$teacher_email) {
					$email = '';
				} else {
					$email = '
						<a href="mailto:' . $data->$teacher_email . '">
							<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Phone
				if (!$data->$teacher_phone) {
					$phone = '';
				} else {
					$phone = '
						<a href="tel:' . $data->$teacher_phone . '">
							<i class="fa fa-phone fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Website
				if (!$data->$teacher_website_url) {
					$website = '';
				} else {
					$website = '
						<a href="' . $data->$teacher_website_url . '" target="' . $this->config->social_links_target . '">
							<i class="fa fa-link fa-fw" aria-hidden="true"></i>
						</a>';
				}

				// Social
				$social_style_class = $this->config->style == '0' ? 'uk-transition-slide-bottom uk-position-bottom uk-overlay uk-overlay-primary' : '';
				if ($data->$teacher_facebook_url ||
					$data->$teacher_twitter_url ||
					$data->$teacher_linkedin_url ||
					$data->$teacher_instagram_url ||
					$data->$teacher_youtube_url ||
					$data->$teacher_github_url ||
					$data->$teacher_email ||
					$data->$teacher_phone ||
					$data->$teacher_website_url
					) {
					$social = '
						<div class="teacher-social ' . $social_style_class . '">
							' . $facebook . '
							' . $twitter . '
							' . $linkedin . '
							' . $instagram . '
							' . $youtube . '
							' . $github . '
							' . $email . '
							' . $phone . '
							' . $website . '
						</div>';
				} else {
					$social = '';
				}

				// Image-Social Container (no need to check for Social Icons, in Style1 they cannot be used without an image)
				if ($image) {
					$image_social_container = '
						<div class="teacher-image-social-container">
							' . $image . '
							' . $social . '
						</div>';
				} else {
					$image_social_container = '';	
				}

				// Item
				if ($this->config->style == '0') {
					$style_item = '
						<div class="card uk-transition-toggle ' . $data->$teacher_css_class . '">
							' . $image_social_container . '
							<div class="card-body">
								' . $name . '
								' . $position . '
								' . $text . '
							</div>
						</div>';
				} else {
					$style_item = '
						<div class="card ' . $data->$teacher_css_class . '">
							' . $image . '
							<div class="card-body">
								' . $name . '
								' . $position . '
								' . $text . '
								' . $social . '
							</div>
						</div>';
				}

				// Teacher Items
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
	    	<div class="teachers-pro-container">
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

        for($i = 1; $i <= $data->teachers; $i++) {
            $field = 'image_teacher' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            file_save_draft_area_files($data->$field, $this->context->id, 'block_purity_teachers_pro', 'teachers', $i,
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
        $fs->delete_area_files($this->context->id, 'block_purity_teachers_pro');
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
