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
 * Purity Course Intro block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_course_intro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_course_intro', 'block_purity_course_intro');
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
			$this->config->style = '0';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_course_intro', 'block_purity_course_intro');            
	        } else {
	        	$this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
	        }
	    }
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
		global $CFG, $DB;

	    if ($this->content !== null) {
	      return $this->content;
	    }

        $course_id = $this->page->course->id;
        $context = context_course::instance($course_id);

	    // Set defaults
	    if (!isset($this->config->show_media)) { $this->config->show_media = '1'; }
	    if (!isset($this->config->media_height)) { $this->config->media_height = '450px'; }
	    if (!isset($this->config->custom_image)) { $this->config->custom_image = null; }
	    if (!isset($this->config->video_url)) { $this->config->video_url = null; }
	    if (!isset($this->config->show_category)) { $this->config->show_category = '1'; }
	    if (!isset($this->config->show_name)) { $this->config->show_name = '1'; }
	    if (!isset($this->config->show_teacher)) { $this->config->show_teacher = '0'; }
	    if (!isset($this->config->teacher)) { $this->config->teacher = null; }
	    if (!isset($this->config->show_date)) { $this->config->show_date = '2'; }
	    if (!isset($this->config->show_enrolments)) { $this->config->show_enrolments = '1'; }
	    if (!isset($this->config->show_summary)) { $this->config->show_summary = '1'; }
	 
	    $this->content = new stdClass;

	    // Get uploaded image
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_purity_course_intro', 'content');
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename <> '.') {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $filename);
                $custom_course_image_url = $url;
            }
        }

        // Set Image URL
	    $course_image_url = $this->get_course_image_url();
	    $course_video_url = format_text($this->config->video_url, FORMAT_HTML, array('filter' => true));

		// Set Course Media Overlay/Mask
		if ($this->config->style == '0') {
			$media_overlay = '';
		} else if ($this->config->style == '1') {
			$media_overlay = '<div class="course-intro-media-overlay"></div>';
		}

	    // Set Course Media
		if ($this->config->show_media == '0') {
			$course_media = '';
		} else if ($this->config->show_media == '1') {
			$course_media = '
	    		<div class="course-intro-media"
	    			style="background-image: url(' . $course_image_url . ');
	    			height: ' . $this->config->media_height . ';
	    		">' . $media_overlay . '</div>';
		} else if ($this->config->show_media == '2') {
			$course_media = '
	    		<div class="course-intro-media"
	    			style="background-image: url(' . $custom_course_image_url . ');
	    			height: ' . $this->config->media_height . ';
	    		">' . $media_overlay . '</div>';
		} else if ($this->config->show_media == '3') {
			$course_media = '
	    		<div class="course-intro-media">
	    			<iframe class="course_video_iframe"
	    				frameborder="0"
	    				allowfullscreen
	    				style="min-height: ' . $this->config->media_height . ';"
	    				src="'. $this->embededVideoHandler($course_video_url) .'"
	    			></iframe>
	    			' . $media_overlay . '
	    		</div>';
	    }

		// Set Course Category
		if ($this->config->show_category == '0') {
			$course_category = '';
		} else if ($this->config->show_category == '1') {
			if ($DB->record_exists('course_categories', array('id' => $this->page->course->category))) {
				$course_category_id = $this->page->course->category;
				$course_category_record = $DB->get_record('course_categories', array('id'=>$course_category_id));
				$course_category_name = format_text($course_category_record->name, FORMAT_HTML, array('filter' => true));
				$course_category_url = $CFG->wwwroot . '/course/index.php?categoryid=' . $course_category_id;

				if ($this->config->style == '0') {
					$course_category = '
						<dd class="course-intro-category" data-toggle="tooltip" title="' . get_string('category', 'block_purity_course_intro') . '">
							<i class="fa fa-folder fa-fw icon" aria-hidden="true"></i>
							<a href="' . $course_category_url . '"><span class="course-intro-category-name">' . $course_category_name . '</span></a>
						</dd>';

					$course_category_style1 = $course_category;
					$course_category_style2 = null;
				} else if ($this->config->style == '1') {
					$course_category = '
						<div class="course-intro-category">
							<a href="' . $course_category_url . '"><span class="course-intro-category-name">' . $course_category_name . '</span></a>
						</div>';

					$course_category_style1 = null;
					$course_category_style2 = $course_category;
				}
			} else {
				$course_category_style1 = null;
				$course_category_style2 = null;
			}
		}

		// Set Course Name
		if ($this->config->show_name == '0') {
			$course_name = '';
		} else if ($this->config->show_name == '1') {
			$course_name = '<h2 class="course-intro-name">' . format_text($this->page->course->fullname, FORMAT_HTML, array('filter' => true)) . '</h2>';
		} else if ($this->config->show_name == '2') {
			$course_name = '<h2 class="course-intro-name">' .  format_text($this->page->course->shortname, FORMAT_HTML, array('filter' => true)) . '</h2>';
		}

		// Set Course Teacher
		if ($this->config->show_teacher == '0') {
			$course_teacher = '';
		} else if ($this->config->show_teacher == '1') {
			$user_data = get_complete_user_data('id', $this->config->teacher);
			$user_full_name = $user_data->firstname . ' ' . $user_data->lastname;
			$user_profile_url = $CFG->wwwroot . '/user/profile.php?id='. $this->config->teacher;

			$course_teacher = '
			<dd class="course-intro-teacher" data-toggle="tooltip" title="' . get_string('teacher', 'block_purity_course_intro') . '">
				<i class="fa fa-user-o fa-fw icon" aria-hidden="true"></i>
				<a href="' . $user_profile_url . '">' . $user_full_name . '</a>
			</dd>';
		}

		// Set Course Date
		if ($this->config->show_date == '0') {
			$course_date = '';
		} else if ($this->config->show_date == '1') {
			$course_date = '
				<dd class="course-intro-date" data-toggle="tooltip" title="' . get_string('created_date', 'block_purity_course_intro') . '">
					<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
					' . userdate($this->page->course->timecreated, get_string('strftimedate', 'langconfig'), 0) . '
				</dd>';
		} else if ($this->config->show_date == '2') {
			$course_date = '
				<dd class="course-intro-date" data-toggle="tooltip" title="' . get_string('last_modified_date', 'block_purity_course_intro') . '">
					<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
					' . userdate($this->page->course->timemodified, get_string('strftimedate', 'langconfig'), 0) . '
				</dd>';
		} else if ($this->config->show_date == '3') {
			$course_date = '
				<dd class="course-intro-date" data-toggle="tooltip" title="' . get_string('start_end_date', 'block_purity_course_intro') . '">
					<i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>
					' . userdate($this->page->course->startdate, get_string('strftimedate', 'langconfig'), 0) . '  -  
					' . userdate($this->page->course->enddate, get_string('strftimedate', 'langconfig'), 0) . '
				</dd>';
		}

		// Set Course Enrollments
		if ($this->config->show_enrolments == '0') {
			$course_enrolments = '';
		} else if ($this->config->show_enrolments == '1') {
			$course_enrolments = '
			<dd class="course-intro-enrolments" data-toggle="tooltip" title="' . get_string('enrolments', 'block_purity_course_intro') . '">
				<i class="fa fa-users fa-fw icon" aria-hidden="true"></i>
				' . count_enrolled_users($context) . '
			</dd>';
		}

		// Set Course Summary
		if ($this->config->show_summary == '0') {
			$course_summary = '';
		} else if ($this->config->show_summary == '1') {
        	$summary_pluginfile_urls = file_rewrite_pluginfile_urls($this->page->course->summary, 'pluginfile.php', $context->id, 'course', 'summary', NULL);
        	$course_summary = '<hr><div class="course-intro-summary">' . format_text($summary_pluginfile_urls, $this->page->course->summaryformat, null) . '</div>';
		} else if ($this->config->show_summary == '2') {
			$course_summary = '<hr><div class="course-intro-summary">' . format_text($this->config->custom_summary['text'], FORMAT_HTML, array('filter' => true)) . '</div>';
		}

		// Set Course Details
		if ($this->config->show_teacher != '0' || $this->config->show_date != '0' || $this->config->show_enrolments != '0' || isset($course_category_style1)) {
			$course_details = '
				<dl class="course-intro-details">
					' . $course_teacher . '
					' . $course_date . '
					' . $course_category_style1 . '
					' . $course_enrolments . '
				</dl>';
		} else {
			$course_details = '';
		}

		// Set Block Content
    $declared_course_category_style2 = isset($course_category_style2) ? $course_category_style2 : '';
		$this->content->text = '
			<div class="course-intro-container">
				' . $course_media . '
				<div class="course-intro-info">
					' . $declared_course_category_style2 . '
					' . $course_name . '
					' . $course_details . '
					' . $course_summary . '
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
		return array('course-view' => true, 'mod-forum-view' => true);
	}


    public function get_course_image_url() {
       global $CFG, $COURSE;
       $url = '';
       require_once( $CFG->libdir . '/filelib.php' );

       $context = context_course::instance( $COURSE->id );
       $fs = get_file_storage();
       $files = $fs->get_area_files( $context->id, 'course', 'overviewfiles', 0 );

       foreach ( $files as $f )
       {
         if ( $f->is_valid_image() )
         {
            $url = moodle_url::make_pluginfile_url( $f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false );
         }
       }

       return $url;
    }

	public function embededVideoHandler($videoUrl) {

		if(!empty($videoUrl)){
			if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
				$vidID = substr($videoUrl, strpos($videoUrl, 'watch?v=') + strlen('watch?v='));
				$vidID = strtok($vidID, '&');
				$vidURL = 'https://www.youtube.com/embed/'.$vidID;
			} elseif (strpos($videoUrl, 'youtu.be') !== false) {
				$vidID = substr($videoUrl, strpos($videoUrl, '.be/') + strlen('.be/'));
				$vidID = strtok($vidID, '&');
				$vidURL = 'https://www.youtube.com/embed/'.$vidID;
			} elseif (strpos($videoUrl, 'youtube.com/embed') !== false) {
				$vidID = substr($videoUrl, strpos($videoUrl, 'embed/') + strlen('embed/'));
				$vidID = strtok($vidID, '/');
				$vidURL = 'https://www.youtube.com/embed/'.$vidID;
			} elseif (strpos($videoUrl, 'vimeo.com/') !== false) {
				$vidID = substr($videoUrl, strpos($videoUrl, '.com/') + strlen('.com/'));
				$vidID = strtok($vidID, '?');
				$vidURL = 'https://player.vimeo.com/video/'.$vidID.'?autoplay=1&loop=0&title=0&color=fff';
			} else {
				$vidURL = null;
			}
			return $vidURL;
		}
		return null;

	}

}
