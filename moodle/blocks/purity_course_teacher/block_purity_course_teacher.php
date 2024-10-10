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
 * Purity Course Teacher block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_course_teacher extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_course_teacher', 'block_purity_course_teacher');
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
        }

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_course_teacher', 'block_purity_course_teacher');            
	        } else {
	        	$this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
	        }
	    }
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
		global $CFG, $OUTPUT, $PAGE, $DB;

	    if ($this->content !== null) {
	      return $this->content;
	    }

	    // Set defaults
	    if (!isset($this->config->show_image)) { $this->config->show_image = '1'; }
	    if (!isset($this->config->show_courses_count)) { $this->config->show_courses_count = '1'; }
	    if (!isset($this->config->show_students_count)) { $this->config->show_students_count = '1'; }
	    if (!isset($this->config->title)) { $this->config->title = ''; }
	    if (!isset($this->config->show_description)) { $this->config->show_description = '1'; }
	    if (!isset($this->config->description_limit)) { $this->config->description_limit = ''; }
	    if (!isset($this->config->social_links_target)) { $this->config->social_links_target = '_self'; }
	    if (!isset($this->config->facebook_url)) { $this->config->facebook_url = ''; }
	    if (!isset($this->config->twitter_url)) { $this->config->twitter_url = ''; }
	    if (!isset($this->config->linkedin_url)) { $this->config->linkedin_url = ''; }
	    if (!isset($this->config->instagram_url)) { $this->config->instagram_url = ''; }
	    if (!isset($this->config->youtube_url)) { $this->config->youtube_url = ''; }
	    if (!isset($this->config->github_url)) { $this->config->github_url = ''; }
	    if (!isset($this->config->email)) { $this->config->email = ''; }
	    if (!isset($this->config->phone)) { $this->config->phone = ''; }
	    if (!isset($this->config->website_url)) { $this->config->website_url = ''; }
	 
	    $this->content = new stdClass;

		// Check if Course Teacher is selected
		if (!$this->config->teacher) {
			$this->content->text = get_string('no_teacher_selected', 'block_purity_course_teacher');
			return $this->content;
		}

		$user_id = $this->config->teacher;

		if (!$DB->record_exists('user', array('id' => $user_id), '*')) {
			$this->content->text = get_string('no_such_user', 'block_purity_course_teacher');
			return $this->content;
		}

		$detailed_user_data = $DB->get_record('user', array('id' => $user_id), '*', MUST_EXIST);
		$user_data = get_complete_user_data('id', $user_id);
		$user_full_name = $user_data->firstname . ' ' . $user_data->lastname;
		$user_profile_url = $CFG->wwwroot . '/user/profile.php?id='. $user_id;
		$user_description = file_rewrite_pluginfile_urls($detailed_user_data->description, 'pluginfile.php', $user_id, 'user', 'profile', null);
		$user_email = $user_data->email;
		$user_phone = $user_data->phone1;
		$user_mobile = $user_data->phone2;
    if (isset($user_data->url)) {
      $user_website = $user_data->url;
    }
		$user_last_access = userdate($user_data->lastaccess);

		// User Image
		if($user_data) {
			$user_image_object = new \user_picture($user_data);
			$user_image_object->size = 300;
			$user_image_url = $user_image_object->get_url($PAGE)->out(false);
		} else {
			$user_image_url = '';
		}

    	// Courses and Students Count
    	$user_enroled_courses = enrol_get_users_courses($user_id);

        $user_enrol_contexts = array();
        foreach($user_enroled_courses as $key => $enrolment) {
        	$user_enrol_contexts[] = $enrolment->ctxid;
        }

        $teacher_role = $DB->get_field('role', 'id', array('shortname' => 'editingteacher'));
        $user_role_assignments_as_teacher = $DB->get_records('role_assignments', ['userid' => $user_id, 'roleid' => $teacher_role]);

        $user_teaching_contexts = new \stdClass();
        foreach($user_enrol_contexts as $key => $context) {
        	if($DB->record_exists('role_assignments', ['userid' => $user_id, 'roleid' => $teacher_role, 'contextid' => $context])){
        		$user_teaching_contexts->$context = $context;
        	}
        }

        $teaching_courses = array();
        foreach ($user_enroled_courses as $key => $enrolment){
        	$current_ctx = $enrolment->ctxid;

        	if(!empty($user_teaching_contexts->$current_ctx) && $enrolment->ctxid == $user_teaching_contexts->$current_ctx){
        		$teaching_courses[$enrolment->id] = $enrolment;
        	}
        }

        $enrolment_count = count($user_enroled_courses);
        $teaching_courses_count = count($user_role_assignments_as_teacher);

        $teaching_students_count = 0;
        foreach($teaching_courses as $key => $course) {
          $course_id = $course->id;

          if ($DB->record_exists('course', array('id' => $course_id))) {
            $context = context_course::instance($course_id);
            $number_of_users = count_enrolled_users($context);
            $teaching_students_count+= $number_of_users;
          }
        }

        // Output
        // Name
		$user_full_name_o = '
			<h3 class="teacher-name">
				<a href="' . $user_profile_url . '">
					' . $user_full_name . '
				</a>
			</h3>';

		// Image
		if ($this->config->show_image == '0') {
			$user_image_o = '';
		} else if ($this->config->show_image == '1') {
        	$user_image_o = '
        		<div class="course-teacher-image-container">
	        		<a href="' . $user_profile_url . '">
	        			<img class="course-teacher-image" src="' . $user_image_url . '" alt="' . $user_full_name . '">
	        		</a>
        		</div>';
		}

		// Courses Count
		if ($this->config->show_courses_count == '0') {
			$user_courses_count_o = '';
		} else if ($this->config->show_courses_count == '1') {
        	$user_courses_count_o = '
				<dd class="course-teacher-courses-count" data-toggle="tooltip" title="' . get_string('courses_count', 'block_purity_course_teacher') . '">
					<i class="fa fa-graduation-cap fa-fw icon" aria-hidden="true"></i>
					' . $teaching_courses_count . '
				</dd>';
		}

		// Students Count
		if ($this->config->show_students_count == '0') {
			$user_students_count_o = '';
		} else if ($this->config->show_students_count == '1') {
        	$user_students_count_o = '
				<dd class="course-teacher-students-count" data-toggle="tooltip" title="' . get_string('students_count', 'block_purity_course_teacher') . '">
					<i class="fa fa-users fa-fw icon" aria-hidden="true"></i>
					' . $teaching_students_count . '
				</dd>';
		}

		// Set User Details
		if ($this->config->show_courses_count != '0' || $this->config->show_students_count != '0') {
			$user_details = '
				<dl class="course-teacher-details">
					' . $user_courses_count_o . '
					' . $user_students_count_o . '
				</dl>';
		} else {
			$user_details = '';
		}


		// Title
		if (!$this->config->title) {
			$user_title_o = '';
		} else {
			$user_title_o = '<div class="course-teacher-title">' . format_text($this->config->title, FORMAT_HTML, array('filter' => true)) . '</div>';
		}

		// Description
		if ($this->config->show_description == '0') {
			$user_description_o = '';
		} else if ($this->config->show_description == '1') {
			if (!$this->config->description_limit) {
				$final_user_description = $user_description;
			} else {
				$final_user_description = substr($user_description, 0, $this->config->description_limit) . '...';
			}
			$user_description_o = '<div class="course-teacher-description">' . $final_user_description . '</div>';
		}

		// Facebook
		if (!$this->config->facebook_url) {
			$user_facebook_o = '';
		} else {
			$user_facebook_o = '
				<a href="' . $this->config->facebook_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-facebook fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Twitter
		if (!$this->config->twitter_url) {
			$user_twitter_o = '';
		} else {
			$user_twitter_o = '
				<a href="' . $this->config->twitter_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-twitter fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// LinkedIn
		if (!$this->config->linkedin_url) {
			$user_linkedin_o = '';
		} else {
			$user_linkedin_o = '
				<a href="' . $this->config->linkedin_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-linkedin fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Instagram
		if (!$this->config->instagram_url) {
			$user_instagram_o = '';
		} else {
			$user_instagram_o = '
				<a href="' . $this->config->instagram_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-instagram fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Youtube
		if (!$this->config->youtube_url) {
			$user_youtube_o = '';
		} else {
			$user_youtube_o = '
				<a href="' . $this->config->youtube_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-youtube fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Github
		if (!$this->config->github_url) {
			$user_github_o = '';
		} else {
			$user_github_o = '
				<a href="' . $this->config->github_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-github fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Email
		if (!$this->config->email) {
			$user_email_o = '';
		} else {
			$user_email_o = '
				<a href="mailto:' . $this->config->email . '">
					<i class="fa fa-envelope fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Phone
		if (!$this->config->phone) {
			$user_phone_o = '';
		} else {
			$user_phone_o = '
				<a href="tel:' . $this->config->phone . '">
					<i class="fa fa-phone fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Website
		if (!$this->config->website_url) {
			$user_website_o = '';
		} else {
			$user_website_o = '
				<a href="' . $this->config->website_url . '" target="' . $this->config->social_links_target . '">
					<i class="fa fa-link fa-fw" aria-hidden="true"></i>
				</a>';
		}

		// Set Contacts
		if ($this->config->facebook_url ||
			$this->config->twitter_url ||
			$this->config->linkedin_url ||
			$this->config->instagram_url ||
			$this->config->youtube_url ||
			$this->config->github_url ||
			$this->config->email ||
			$this->config->phone ||
			$this->config->website_url
			) {
			$user_contacts = '
				<div class="course-teacher-contacts">
					' . $user_facebook_o . '
					' . $user_twitter_o . '
					' . $user_linkedin_o . '
					' . $user_instagram_o . '
					' . $user_youtube_o . '
					' . $user_github_o . '
					' . $user_email_o . '
					' . $user_phone_o . '
					' . $user_website_o . '
				</div>';
		} else {
			$user_contacts = '';
		}

        $course_teacher = '
        	<div class="course-teacher-container">
        		<div class="course-teacher-image-info-wrapper">
        			' . $user_image_o . '
	        		<div class="course-teacher-info">
	        			<div class="course-teacher-name-details-wrapper">
		        			' . $user_full_name_o . '
		        			' . $user_details . '
		        		</div>
		        		' . $user_title_o . '
		        		' . $user_description_o . '
		        		' . $user_contacts . '
	        		</div>
	        	</div>
        	</div>';

		$this->content->text = $course_teacher;

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
