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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . "/course/format/weeks/renderer.php");

class theme_purity_format_weeks_renderer extends format_weeks_renderer {

  // Provide theme settings to course mustache templates
  protected function render_content(\renderable $widget) {
    $theme = theme_config::load('purity');

    $data = $widget->export_for_template($this);

    $data->initialsection->is_first_section = true;

    if ($theme->settings->weeksformatlayout == 'all_but_first_collapsed') {
      $data->all_but_first_collapsed = true;
    } else if ($theme->settings->weeksformatlayout == 'all_expanded') {
      $data->all_expanded = true;
    } else if ($theme->settings->weeksformatlayout == 'default') {
      $data->default_layout = true;
    } else {
      $data->all_collapsed = true;
    }

    return $this->render_from_template($widget->get_template_name($this), $data);
  }

    /**
     * Generate the starting container html for a list of sections
     * @return string HTML to output.
     */
    protected function start_section_list() {
    	global $PAGE;

        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout != 'default') && ($PAGE->theme->settings->showsectioncompletionicon == 'show')) {
        	$element = html_writer::start_tag('ul', array('class' => 'weeks purity-collapsible show-completion-icons'));
        } elseif (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout != 'default')) {
            $element = html_writer::start_tag('ul', array('class' => 'weeks purity-collapsible'));
        } else {
        	$element = html_writer::start_tag('ul', array('class' => 'weeks purity-default'));
        }


        return $element;
    }



    /**
     * Generate the display of the header part of a section before
     * course modules are included
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param bool $onsectionpage true if being printed on a single-section page
     * @param int $sectionreturn The section to return to after an action
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn=null) {
    	global $PAGE;

        $o = '';
        $currenttext = '';
        $sectionstyle = '';

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            }
            if (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }

        $o .= html_writer::start_tag('li', [
            'id' => 'section-'.$section->section,
            'class' => 'section main clearfix'.$sectionstyle,
            'role' => 'region',
            'aria-labelledby' => "sectionid-{$section->id}-title",
            'data-sectionid' => $section->section,
            'data-sectionreturnid' => $sectionreturn
        ]);

        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_collapsed')) {

			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="false" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse">
							<div class="card-body">';

        } elseif (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_but_first_collapsed')) {

			$isFirst = $section->section == 0 ? true : false;
			$showClass = $isFirst ? 'show' : '';
			$ariaExpanded = $isFirst ? 'true' : 'false';

			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="'.$ariaExpanded.'" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse '.$showClass.'">
							<div class="card-body">';

        } elseif (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_expanded')) {
        	
			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="true" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse show">
							<div class="card-body">';

        }


        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));

        // When not on a section page, we display the section titles except the general section if null
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));

        // When on a section page, we only display the general section title, if title is not the default one
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));

        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }
        $sectionname = html_writer::tag('span', $this->section_title($section, $course));
        $o .= $this->output->heading($sectionname, 3, 'sectionname' . $classes, "sectionid-{$section->id}-title");

        $o .= $this->section_availability($section);

        $o .= html_writer::start_tag('div', array('class' => 'summary'));
        if ($section->uservisible || $section->visible) {
            // Show summary if section is available or has availability restriction information.
            // Do not show summary if section is hidden but we still display it because of course setting
            // "Hidden sections are shown in collapsed form".
            $o .= $this->format_summary_text($section);
        }
        $o .= html_writer::end_tag('div');

        return $o;
    }



    /**
     * Generate the display of the footer part of a section
     *
     * @return string HTML to output.
     */
    protected function section_footer() {
    	global $PAGE;

        $o = html_writer::end_tag('div');

        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_collapsed' || $PAGE->theme->settings->weeksformatlayout == 'all_but_first_collapsed')) {
        	$o.= '</div></div></div></div>';
        }

        $o.= html_writer::end_tag('li');

        return $o;
    }



    /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
    	global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection)) || !$sectioninfo->uservisible) {
            // This section doesn't exist or is not available for the user.
            // We actually already check this in course/view.php but just in case exit from this function as well.
            print_error('unknowncoursesection', 'error', course_get_url($course),
                format_string($course->fullname));
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or !empty($modinfo->sections[0]) or $this->page->user_is_editing()) {
            echo $this->start_section_list();
            echo $this->section_header($thissection, $course, true, $displaysection);
            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section'));

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = '';
        $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation navigationtitle'));
        // $sectiontitle .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        // $sectiontitle .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        
        // Title attributes
        $classes = 'sectionname';
        if (!$thissection->visible) {
            $classes .= ' dimmed_text';
        }
        $sectionname = html_writer::tag('span', $this->section_title_without_link($thissection, $course));
        $sectiontitle .= $this->output->heading($sectionname, 3, $classes);

        $sectiontitle .= html_writer::end_tag('div');

        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'default')) {
        	echo $sectiontitle;
        }

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
        echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection),
            array('class' => 'mdl-align'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');
    }



    /**
     * Generate a summary of a section for display on the 'course index page'
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_summary($section, $course, $mods) {
    	global $PAGE;

        $classattr = 'section main section-summary clearfix';
        $linkclasses = '';

        // If section is hidden then display grey section link
        if (!$section->visible) {
            $classattr .= ' hidden';
            $linkclasses .= ' dimmed_text';
        } else if (course_get_format($course)->is_section_current($section)) {
            $classattr .= ' current';
        }

        $title = get_section_name($course, $section);
        $title_raw = get_section_name($course, $section);
        
        $o = '';
        $o .= html_writer::start_tag('li', [
            'id' => 'section-'.$section->section,
            'class' => $classattr,
            'role' => 'region',
            'aria-label' => $title,
            'data-sectionid' => $section->section
        ]);


        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_collapsed')) {

			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="false" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse">
							<div class="card-body">';

        } elseif (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_but_first_collapsed')) {

			$isFirst = $section->section == 0 ? true : false;
			$showClass = $isFirst ? 'show' : '';
			$ariaExpanded = $isFirst ? 'true' : 'false';

			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="'.$ariaExpanded.'" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse '.$showClass.'">
							<div class="card-body">';

        } elseif (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_expanded')) {
        	
			$o .= '<div class="accordion">
						<div class="card">
							<div class="card-header" data-toggle="collapse" data-target="#section'.$section->section.'" aria-expanded="true" aria-controls="section'.$section->section.'">
								<h3 class="mb-0">'.get_section_name($course, $section).'</h3>
							</div>

						<div id="section'.$section->section.'" class="collapse show">
							<div class="card-body">';

        }




        $o .= html_writer::tag('div', '', array('class' => 'left side'));
        $o .= html_writer::tag('div', '', array('class' => 'right side'));
        $o .= html_writer::start_tag('div', array('class' => 'content'));

        $o.= html_writer::start_tag('div', array('class' => 'title-summary-button-wrapper'));
        $o.= html_writer::start_tag('div', array('class' => 'title-summary-wrapper'));

        if ($section->uservisible) {
            $title = html_writer::tag('a', $title,
                    array('href' => course_get_url($course, $section->section), 'class' => $linkclasses));
        }
        $o .= $this->output->heading($title, 3, 'section-title');

        $o .= $this->section_availability($section);
        $o.= html_writer::start_tag('div', array('class' => 'summarytext'));

        if ($section->uservisible || $section->visible) {
            // Show summary if section is available or has availability restriction information.
            // Do not show summary if section is hidden but we still display it because of course setting
            // "Hidden sections are shown in collapsed form".
            $o .= $this->format_summary_text($section);
        }
        $o.= html_writer::end_tag('div');

        $o.= html_writer::end_tag('div');

        $o.= html_writer::start_tag('div', array('class' => 'button-wrapper'));
        $o .= '<a href="'.course_get_url($course, $section->section).'" class="btn btn-primary">'.get_string('resourcedisplayopen').' '.$title_raw.'</a>';
        $o.= html_writer::end_tag('div');

        $o.= html_writer::end_tag('div');

        $o.= $this->section_activity_summary($section, $course, null);

        $o .= html_writer::end_tag('div');

        if (isset($PAGE->theme->settings->weeksformatlayout) && ($PAGE->theme->settings->weeksformatlayout == 'all_collapsed' || $PAGE->theme->settings->weeksformatlayout == 'all_but_first_collapsed')) {
        	$o.= '</div></div></div></div>';
        }

        $o .= html_writer::end_tag('li');

        return $o;
    }



    /**
     * Generate a summary of the activites in a section
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course the course record from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_activity_summary($section, $course, $mods) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section:
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                            $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                    }
                }
            }
        }

        if (empty($sectionmods)) {
            // No sections
            return '';
        }

        // Output section activities summary:
        $o = '';
        $o.= html_writer::start_tag('ul', array('class' => 'section-summary-activities'));
        foreach ($sectionmods as $mod) {
            $o.= html_writer::start_tag('li', array('class' => 'activity-count'));
            $o.= '<span class="icon fa fa-graduation-cap fa-fw"></span> '.$mod['name'].': '.$mod['count'];
            $o.= html_writer::end_tag('li');
        }

        // Output section completion data
        if ($total > 0) {
            $a = new stdClass;
            $a->complete = $complete;
            $a->total = $total;

            $o.= html_writer::start_tag('li', array('class' => 'activity-count'));
            $o.= '<span class="icon fa fa-check-square-o fa-fw"></span> '.get_string('progresstotal', 'completion', $a);
            // $o.= html_writer::tag('span', get_string('progresstotal', 'completion', $a), array('class' => 'activity-countssss'));
            $o.= html_writer::end_tag('li');
        }
        $o.= html_writer::end_tag('ul');

        return $o;
    }

}