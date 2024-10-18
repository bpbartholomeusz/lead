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
 * Purity Blogs PRO block main class.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .'/blog/lib.php');
require_once($CFG->dirroot .'/blog/locallib.php');

class block_purity_blogs_pro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_blogs_pro', 'block_purity_blogs_pro');
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
			$this->config->custom_title = 'Our Latest Blogs';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->behaviour = 'slider';
			$this->config->items_per_row = '3';
			$this->config->style = '0';
			$this->config->title_limit = '32';
			$this->config->summary_limit = '100';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_blogs_pro', 'block_purity_blogs_pro');            
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

		// Set defaults
		if (!isset($this->config->autoplay)) { $this->config->autoplay = 'true'; }
		if (!isset($this->config->autoplay_interval)) { $this->config->autoplay_interval = '6000'; }
		if (!isset($this->config->pause_hover)) { $this->config->pause_hover = 'true'; }
		if (!isset($this->config->show_image)) { $this->config->show_image = '1'; }
		if (!isset($this->config->image_height)) { $this->config->image_height = '200px'; }
		if (!isset($this->config->show_title)) { $this->config->show_title = '1'; }
		if (!isset($this->config->show_summary)) { $this->config->show_summary = '1'; }
		if (!isset($this->config->show_author)) { $this->config->show_author = '1'; }
		if (!isset($this->config->show_date)) { $this->config->show_date = '1'; }
		if (!isset($this->config->button_text)) { $this->config->button_text = ''; }
		if (!isset($this->config->button_link)) { $this->config->button_link = ''; }
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

		// Check if Blogs are enabled
		if (empty($CFG->enableblogs)) {
			$this->content->text = get_string('blogdisable', 'blog');
			return $this->content;
		} else if ($CFG->bloglevel < BLOG_GLOBAL_LEVEL and (!isloggedin() or isguestuser())) {
			return $this->content;
		}

		// Get Blog Entries
		$bloglisting = new blog_listing();
		$entries = $bloglisting->get_entries();

		// Set Blog Image Overlay/Mask
		if ($this->config->style == '0') {
			$image_overlay = '';
		} else if ($this->config->style == '1') {
			$image_overlay = '<div class="blog-image-overlay"></div>';
		}

		$items = '';
		foreach ($entries as $entryid => $entry) {

			$blogentry = new blog_entry($entryid);
			$blogattachments = $blogentry->get_attachments();

			// Blog Image
			$blog_image_url = $blogattachments[0]->url;

			// Blog Data
			$blog_title = $entry->subject;
			$blog_summary = strip_tags($entry->summary);
			$blog_created = $entry->created;
			$blog_updated = $entry->lastmodified;
			$blog_url = new moodle_url('/blog/index.php', array('entryid' => $entryid));

			// Blog Author
			$author_id = $entry->useridalias;
			$author_full_name = $entry->firstname . ' ' . $entry->lastname;
			$author_profile_url = $CFG->wwwroot . '/user/profile.php?id='. $author_id;

			// Image
			if ($this->config->show_image == '1') {
				$image = '
					<a href="' . $blog_url . '">
						<div class="blog-image card-img-top" style="background-image: url(' . $blog_image_url . '); height: ' . $this->config->image_height . ';">
							' . $image_overlay . '
						</div>
					</a>';
			} else {
				$image = '';
			}

			// Title
			if ($this->config->show_title == '0') {
				$title = '';
			} else if ($this->config->show_title == '1') {
				if (!$this->config->title_limit) {
					$title = '<h3 class="blog-title"><a href="' . $blog_url . '">' . $blog_title . '</a></h3>';
				} else {
					$dots_string = $this->config->title_limit > strlen($blog_title) ? '' : '...';
					$title = '<h3 class="blog-title"><a href="' . $blog_url . '">' . trim(substr($blog_title, 0, $this->config->title_limit)) . '' . $dots_string . '</a></h3>';
				}
			}

			// Summary
			if ($this->config->show_summary == '1') {
				if (!$this->config->summary_limit) {
					$summary = '<div class="blog-summary">' . $blog_summary . '</div>';
				} else {
					$summary = '<div class="blog-summary">' . trim(substr($blog_summary, 0, $this->config->summary_limit)) . '...</div>';
				}
			} else {
				$summary = '';
			}

			// Author
			if ($this->config->show_author == '1') {
				$author = '
					<div class="blog-author" data-toggle="tooltip" title="' . get_string('written_by', 'block_purity_blogs_pro') . '">
						<i class="fa fa-user-o fa-fw icon" aria-hidden="true"></i>
						<a href="' . $author_profile_url . '">' . $author_full_name . '</a>
					</div>';
			} else {
				$author = '';
			}

			// Date
			if ($this->config->show_date == '0') {
				$date = '';
			} else if ($this->config->show_date == '1') {
				$date = '
					<div class="blog-date" data-toggle="tooltip" title="' . get_string('created_date', 'block_purity_blogs_pro') . '">
						<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
						' . userdate($blog_created, get_string('strftimedatefullshort', 'langconfig')) . '
					</div>';
			} else if ($this->config->show_date == '2') {
				$date = '
					<div class="blog-date" data-toggle="tooltip" title="' . get_string('last_modified_date', 'block_purity_blogs_pro') . '">
						<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
						' . userdate($blog_updated, get_string('strftimedatefullshort', 'langconfig')) . '
					</div>';
			}

			// Author-Date Container
			if ($author || $date) {
				$ad_container = '
					<div class="blog-ad-container">
						' . $author . '
						' . $date . '
					</div>';
			} else {
				$ad_container = '';
			}

			// Item
			if ($this->config->style == '0') {
				$style_item = '
					<div class="card">
						' . $image . '
						<div class="card-body">
							' . $title . '
							' . $summary . '
							' . $ad_container . '
						</div>
					</div>';
			} else {
				$style_item = '
					<div class="card">
						' . $image . '
						<div class="card-style2-wrapper">
							<div class="card-body">
								' . $title . '
								' . $summary . '
								' . $ad_container . '
							</div>
						</div>
					</div>';
			}

			// Blog Items
			if ($this->config->behaviour == 'slider') {
				$items .= '<li>' . $style_item . '</li>';
			} else {
				$items .= '<div class="uk-width-1-' . $this->config->items_per_row . '@m">' . $style_item . '</div>';
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

		// Button
		if ($this->config->button_text) {
			$button = '
				<div class="button-container">
					<a class="btn ' . $this->config->button_style . '" href="' . $this->config->button_link . '" target="' . $this->config->button_target . '">' . format_text($this->config->button_text, FORMAT_HTML, array('filter' => true)) . '</a>
				</div>';
		} else {
			$button = '';
		}

	    $this->content->text = '
	    	<div class="courses-pro-container">
	    		' . $block_intro . '
	    		' . $final_items . '
	    		' . $button . '
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
