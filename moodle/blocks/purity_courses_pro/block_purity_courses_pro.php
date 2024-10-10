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
 * Purity Courses PRO block main class.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/course/renderer.php');
include_once($CFG->dirroot . '/course/lib.php');

class block_purity_courses_pro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_courses_pro', 'block_purity_courses_pro');
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
			$this->config->custom_title = 'Our Featured Courses';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->behaviour = 'slider';
			$this->config->items_per_row = '3';
			$this->config->style = '0';
			$this->config->title_limit = '32';
			$this->config->summary_limit = '100';
			$this->config->show_price = '0';
			$this->config->currency_symbol = '';
			$this->config->price_accent_color = 'primary';
		}

		if (isset($this->config)) {
			if (empty($this->config->custom_title)) {
				$this->title = get_string('purity_courses_pro', 'block_purity_courses_pro');            
			} else {
				$this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
			}
		}
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
		global $CFG, $DB, $PAGE;

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
		if (!isset($this->config->title_limit)) { $this->config->title_limit = '33'; }
		if (!isset($this->config->show_summary)) { $this->config->show_summary = '1'; }
		if (!isset($this->config->summary_limit)) { $this->config->summary_limit = '100'; }
		if (!isset($this->config->show_teacher)) { $this->config->show_teacher = '0'; }
		if (!isset($this->config->show_date)) { $this->config->show_date = '2'; }
		if (!isset($this->config->show_category)) { $this->config->show_category = '1'; }
		if (!isset($this->config->show_enrolments)) { $this->config->show_enrolments = '1'; }
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

		// Check if Courses are selected
		if (!$this->config->courses) {
			$this->content->text = get_string('no_courses_selected', 'block_purity_courses_pro');
			return $this->content;
		}

		$courses = $this->config->courses;
		$chelper = new coursecat_helper();

		// Set Course Image Overlay/Mask
		if ($this->config->style == '0') {
			$image_overlay = '';
		} else if ($this->config->style == '1') {
			$image_overlay = '<div class="course-image-overlay"></div>';
		}

		$items = '';
		foreach ($courses as $course_id) {

			// Check if course exists
			if (!$DB->record_exists('course', array('id' => $course_id))) {
				continue;
			}

			// Course Image
			$course_image_url = $this->course_image_url($course_id);

			// Course Data
			$course_record = $DB->get_record('course', array('id' => $course_id), '*', MUST_EXIST);
			$course_element = new core_course_list_element($course_record);
			$course_context = context_course::instance($course_id);

			$course_short_name = format_text($course_record->shortname, FORMAT_HTML, array('filter' => true));
			$course_full_name = format_text($course_record->fullname, FORMAT_HTML, array('filter' => true));
			$course_summary = strip_tags($chelper->get_course_formatted_summary($course_element, array('noclean' => true, 'para' => false)));
			$course_created = $course_record->timecreated;
			$course_updated = $course_record->timemodified;
			$course_start = $course_record->startdate;
			$course_end = $course_record->enddate;
			$course_enrolments_count = count_enrolled_users($course_context);
			$course_url = new moodle_url('/course/view.php', array('id' => $course_id));

			// Course Category
			if ($DB->record_exists('course_categories', array('id' => $course_record->category))) {
				$course_category_id = $course_record->category;
				$course_category_record = $DB->get_record('course_categories', array('id'=>$course_category_id));
				$course_category_name = format_text($course_category_record->name, FORMAT_HTML, array('filter' => true));
				$course_category_url = $CFG->wwwroot . '/course/index.php?categoryid=' . $course_category_id;
			} else {
				$course_category_name = '';
				$course_category_url = '';
			}

			// Course Teacher
			if ($course_element->has_course_contacts()) {
				$tmp = $course_element->get_course_contacts();
				$teacher_object = reset($tmp);
				$teacher_id = $teacher_object['user']->id;

				$teacher_data = get_complete_user_data('id', $teacher_id);
				$teacher_full_name = $teacher_data->firstname . ' ' . $teacher_data->lastname;
				$teacher_profile_url = $CFG->wwwroot . '/user/profile.php?id='. $teacher_id;

				$teacher_image_object = new \user_picture($teacher_data);
				$teacher_image_object->size = 300;
				$teacher_image_url = $teacher_image_object->get_url($PAGE)->out(false);
			}

			// Course Price
			$course_price_show_class = $this->config->show_price;
			$course_price_accent_color = $this->config->price_accent_color;
			$course_has_price = 0;
			$enrol_instances = enrol_get_instances($course_id, true);
			foreach ($enrol_instances as $key => $instance) {
				if (!empty($instance->cost)) {
					$course_cost = $instance->cost;
					$course_currency = $instance->currency;
					$course_enrol_method = $instance->enrol;
					$course_has_price = 1;
				}
			}
			if ($course_has_price) {
				if (!empty($this->config->currency_symbol)) {
					$course_price_symbol = format_text($this->config->currency_symbol, FORMAT_HTML, array('filter' => true));
					$course_price = $course_price_symbol . '' . $course_cost;
				} else {
					$course_price = $course_cost . ' ' . $course_currency;
				}

				$course_price_o = '<span class="course-price-badge bg-' . $course_price_accent_color . ' ' . $course_price_show_class . '">' . $course_price . '</span>';
			} else {
				$course_price_o = '<span class="course-price-badge course-free bg-' . $course_price_accent_color . ' ' . $course_price_show_class . '">' . get_string('course_cost_free', 'theme_purity') .'</span>';
			}

			// Price
			$course_price_top = '';
			$course_price_bottom = '';
			if ($this->config->show_price == 'show-top') {
				$course_price_top = $course_price_o;
			} else if ($this->config->show_price == 'show-bottom') {
				$course_price_bottom = $course_price_o;
			}

			// Image
			if ($this->config->show_image == '1') {
				$image = '
					<a href="' . $course_url . '">
						<div class="course-image card-img-top" style="background-image: url(' . $course_image_url . '); height: ' . $this->config->image_height . ';">
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
					$title = '<h3 class="course-title"><a href="' . $course_url . '">' . $course_full_name . '</a></h3>';
				} else {
					$dots_string = $this->config->title_limit > strlen($course_full_name) ? '' : '...';
					$title = '<h3 class="course-title"><a href="' . $course_url . '">' . trim(substr($course_full_name, 0, $this->config->title_limit)) . '' . $dots_string . '</a></h3>';
				}
			} else if ($this->config->show_title == '2') {
				if (!$this->config->title_limit) {
					$title = '<h3 class="course-title"><a href="' . $course_url . '">' . $course_short_name . '</a></h3>';
				} else {
					$dots_string = $this->config->title_limit > strlen($course_short_name) ? '' : '...';
					$title = '<h3 class="course-title"><a href="' . $course_url . '">' . trim(substr($course_short_name, 0, $this->config->title_limit)) . '' . $dots_string . '</a></h3>';
				}
			}

			// Summary
			if ($this->config->show_summary == '1') {
				if (!$this->config->summary_limit) {
					$summary = '<div class="course-summary">' . $course_summary . '</div>';
				} else {
					$summary = '<div class="course-summary">' . trim(substr($course_summary, 0, $this->config->summary_limit)) . '...</div>';
				}
			} else {
				$summary = '';
			}

			// Teacher
			if ($this->config->show_teacher == '1') {
				$teacher = '
					<div class="course-teacher" data-toggle="tooltip" title="' . get_string('teacher', 'block_purity_courses_pro') . '">
						<i class="fa fa-user-o fa-fw icon" aria-hidden="true"></i>
						<a href="' . $teacher_profile_url . '">' . $teacher_full_name . '</a>
					</div>';
			} else {
				$teacher = '';
			}

			// Date
			if ($this->config->show_date == '0') {
				$date = '';
			} else if ($this->config->show_date == '1') {
				$date = '
					<div class="course-date" data-toggle="tooltip" title="' . get_string('created_date', 'block_purity_courses_pro') . '">
						<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
						' . userdate($course_created, get_string('strftimedatefullshort', 'langconfig')) . '
					</div>';
			} else if ($this->config->show_date == '2') {
				$date = '
					<div class="course-date" data-toggle="tooltip" title="' . get_string('last_modified_date', 'block_purity_courses_pro') . '">
						<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
						' . userdate($course_updated, get_string('strftimedatefullshort', 'langconfig')) . '
					</div>';
			} else if ($this->config->show_date == '3') {
				$date = '
					<div class="course-date" data-toggle="tooltip" title="' . get_string('start_end_date', 'block_purity_courses_pro') . '">
						<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
						' . userdate($course_start, get_string('strftimedatefullshort', 'langconfig')) . '  -  
						' . userdate($course_end, get_string('strftimedatefullshort', 'langconfig')) . '
					</div>';
			}

			// Category
			if ($this->config->show_category == '1') {
				$category = '
					<div class="course-category" data-toggle="tooltip" title="' . get_string('category', 'block_purity_courses_pro') . '">
						<i class="fa fa-folder fa-fw icon" aria-hidden="true"></i>
						<a href="' . $course_category_url . '">' . $course_category_name . '</a>
					</div>';
			} else {
				$category = '';
			}

			// Enrolments
			if ($this->config->show_enrolments == '1') {
				$enrolments = '
					<div class="course-enrolments" data-toggle="tooltip" title="' . get_string('enrolments', 'block_purity_courses_pro') . '">
						<i class="fa fa-users fa-fw icon" aria-hidden="true"></i>
						' . $course_enrolments_count . '
					</div>';
			} else {
				$enrolments = '';
			}

			// Teacher-Date-Price Container
			if ($teacher || $date || $course_price_bottom) {
				$td_container = '
					<div class="course-td-container">
						' . $teacher . '
						' . $date . '
						' . $course_price_bottom . '
					</div>';
			} else {
				$td_container = '';
			}

			// Enrolments-Category Container (Footer)
			if ($category || $enrolments) {
				$ec_container = '
					<div class="card-footer">
						<div class="course-ec-container">
							' . $category . '
							' . $enrolments . '
						</div>
					</div>';
			} else {
				$ec_container = '';
			}

			// Item
			if ($this->config->style == '0') {
				$style_item = '
					<div class="card">
						' . $image . '
						' . $course_price_top . '
						<div class="card-body">
							' . $title . '
							' . $summary . '
							' . $td_container . '
						</div>
						' . $ec_container . '
					</div>';
			} else {
				$style_item = '
					<div class="card">
						' . $image . '
						' . $course_price_top . '
						<div class="card-style2-wrapper">
							<div class="card-body">
								' . $title . '
								' . $summary . '
								' . $td_container . '
							</div>
							' . $ec_container . '
						</div>
					</div>';
			}

			// Course Items
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

	// Get Course image url by Course ID
	public function course_image_url($courseid) {
		global $DB, $CFG;

		$courserecord = $DB->get_record('course', array('id' => $courseid));
		$course = new core_course_list_element($courserecord);

		foreach ($course->get_course_overviewfiles() as $file) {
			$isimage = $file->is_valid_image();
			$url = file_encode_url("$CFG->wwwroot/pluginfile.php",
			    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
			    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
			if ($isimage) {
			    return $url;
			} 
		}
	}

}
