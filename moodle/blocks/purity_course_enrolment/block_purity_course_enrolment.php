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
 * Purity Course Enrolment block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_course_enrolment extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_course_enrolment', 'block_purity_course_enrolment');
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
			$this->config->show_price = '0';
			$this->config->currency_symbol = '';
			$this->config->price_accent_color = 'primary';
		}

		if (isset($this->config)) {
			if (empty($this->config->custom_title)) {
				$this->title = get_string('purity_course_enrolment', 'block_purity_course_enrolment');            
			} else {
				$this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
			}
		}
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
        global $DB, $USER, $CFG;

        if ($this->content !== null) {
          return $this->content;
        }

        $course_id = $this->page->course->id;
        $context = context_course::instance($course_id);

        if (!$DB->record_exists('course', array('id' => $course_id))) {
            $this->content->text = '';
            return $this->content;
        }

        $course_record = $DB->get_record('course', array('id' => $course_id));
        $course_element = new core_course_list_element($course_record);

        $current_user_id = $USER->id;

        // Enrol, Price, Check if self enrolment is available
        $enrol_instances = enrol_get_instances($course_id, true);
        $instance = '';
        $can_self_enrol = 0;
        $course_price_accent_color = $this->config->price_accent_color;
        $course_has_price = 0;
        foreach ($enrol_instances as $key => $inst) {
            if ($inst->enrol == 'self') {
                $instance = $inst;
                $can_self_enrol = 1;
            }

            if (!empty($inst->cost)) {
                $course_cost = $inst->cost;
                $course_currency = $inst->currency;
                $course_enrol_method = $inst->enrol;
                $course_has_price = 1;
            }
        }
        $plugin = enrol_get_plugin('self');
        $enrol_url = new moodle_url('/enrol/index.php', array('id'=>$course_id));
        if ($instance) {
            $unenrol_url = $plugin->get_unenrolself_link($instance);
        }

        if ($course_has_price) {
            if (!empty($this->config->currency_symbol)) {
                $course_price_symbol = format_text($this->config->currency_symbol, FORMAT_HTML, array('filter' => true));
                $course_price = $course_price_symbol . '' . $course_cost;
            } else {
                $course_price = $course_cost . ' ' . $course_currency;
            }

            $course_price_o = '<div class="course-price text-' . $course_price_accent_color . '">' . $course_price . '</div>';
        } else {
            $course_price_o = '<div class="course-price course-free text-' . $course_price_accent_color . '">' . get_string('course_cost_free', 'theme_purity') .'</div>';
        }

        $course_contacts = array();
        if ($course_element->has_course_contacts()) {
            foreach ($course_element->get_course_contacts() as $key => $course_contact) {
              $course_contacts[$key] = new \stdClass();
              $course_contacts[$key]->userId = $course_contact['user']->id;
              $course_contacts[$key]->username = $course_contact['user']->username;
              $course_contacts[$key]->name = $course_contact['user']->firstname . ' ' . $course_contact['user']->lastname;
              $course_contacts[$key]->role = $course_contact['role']->displayname;
            }
        }

        // Set defaults
        if (!isset($this->config->teacher)) { $this->config->teacher = null; }

        $this->content = new stdClass;

        // Do not show the block if user is not logged in
        // if (isguestuser() || !isloggedin()) {
        //     $this->content->text = '';
         
        //     return $this->content;
        // }

        // Set Course Teacher
        if (!$this->config->teacher) {
            $course_teacher = '';
        } else {
            $user_data = get_complete_user_data('id', $this->config->teacher);
            $user_full_name = $user_data->firstname . ' ' . $user_data->lastname;
            $user_profile_url = $CFG->wwwroot . '/user/profile.php?id='. $this->config->teacher;

            $course_teacher = '<a href="' . $user_profile_url . '">' . $user_full_name . '</a>';
        }

        // Current user status
        if(
            function_exists('isguestuser') &&
            !isguestuser() &&
            isloggedin() &&
            is_enrolled($context, $USER, '', true) &&
            isset($course_contacts[$current_user_id]) &&
            ($current_user_id == $course_contacts[$current_user_id]->userId)
            ){

            $user_status = 'teacher';
        } else if (is_enrolled($context, $USER, '', false)) {
            $user_status = 'enrolled';
        } else if (!is_enrolled($context, $USER, '', false)) {
            $user_status = 'not_enrolled';
        }

        $output = '';

        if ($this->config->show_price) {
            $output .= '
                <div class="course-enrolment-price-container">
                    <div class="course-enrolment-price-text">' . get_string('price', 'block_purity_course_enrolment') . '</div>
                    <div class="course-enrolment-price">' . $course_price_o . '</div>
                </div>';
        }

        if ($user_status == 'teacher') {
            $output .= '<div class="alert alert-success" role="alert"><i class="fa fa-info-circle fa-fw icon" aria-hidden="true"></i>' . get_string('teaching', 'block_purity_course_enrolment') . '</div>';
        } else if ($user_status == 'enrolled' && $can_self_enrol == 0 && !$course_has_price) {
            $output .= '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle fa-fw icon" aria-hidden="true"></i>' . get_string('noselfunenrolment', 'block_purity_course_enrolment');

            if ($course_teacher) {
                $output .= ' ' . get_string('moredetails', 'block_purity_course_enrolment') . $course_teacher . '.';
            }

            $output .= '</div>';
        } else if ($can_self_enrol || $course_has_price) {
            if ($user_status == 'enrolled' && $can_self_enrol) {
                $output .= '<a class="btn btn-secondary btn-block" href="' . $unenrol_url . '">' . get_string('unenrol', 'block_purity_course_enrolment') . '</a>';
            } else if ($user_status == 'enrolled' && $course_has_price) {
                $output .= '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle fa-fw icon" aria-hidden="true"></i>' . get_string('alreadypurchased', 'block_purity_course_enrolment');
            } else if ($user_status == 'not_enrolled') {
                $output .= '<a class="btn btn-primary btn-block" href="' . $enrol_url . '">' . get_string('enrol', 'block_purity_course_enrolment') . '</a>';
            }
        } else {
            $output .= '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle fa-fw icon" aria-hidden="true"></i>' . get_string('noselfenrolment', 'block_purity_course_enrolment');

            if ($course_teacher) {
                $output .= ' ' . get_string('moredetails', 'block_purity_course_enrolment') . $course_teacher . '.';
            }

            $output .= '</div>';
        }

        $this->content->text = $output;

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
