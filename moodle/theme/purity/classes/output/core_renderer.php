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

namespace theme_purity\output;

use theme_config;
use context_header;
use context_system;
use context_course;
use html_writer;
use pix_icon;
use action_menu;
use action_menu_filler;
use action_menu_link_secondary;
use core_text;
use custom_menu;
use moodle_url;

defined('MOODLE_INTERNAL') || die;


class core_renderer extends \core_renderer {

    /**
     * Return a filearea image URL, if any.
     *
     * @return moodle_url|false
     */
    public function get_filearea_image_url($filearea) {
        $theme = theme_config::load('purity');

        return $theme->setting_file_url($filearea, $filearea);
    }

    /**
     * Whether we should display the logo in the navbar.
     * We will when there are no main logos, and we have compact logo.
     *
     * @return bool
     */
    public function should_display_header_logo() {
        if ($this->get_compact_logo_url() || $this->get_filearea_image_url('headerlogo')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Renders the header bar.
     *
     * @param context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(context_header $contextheader) {

        $showheader = empty($this->page->layout_options['nocontextheader']);
        if (!$showheader) {
            return '';
        }

        // All the html stuff goes here.
        $html = html_writer::start_div('page-context-header');

        // Image data.
        if (isset($contextheader->imagedata)) {
            // Header specific image.
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image');
        }

        // Headings.
        if (!isset($contextheader->heading)) {
            $headings = $this->heading($this->page->heading, $contextheader->headinglevel);
        } else {
            $headings = $this->heading($contextheader->heading, $contextheader->headinglevel);
        }

        $html .= html_writer::tag('div', $headings, array('class' => 'page-header-headings'));

        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsmall',
                        'role' => 'presentation'
                    ));
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();

        return $html;
    }

    // Get Google Font URL
    public function get_google_font_url() {
        global $PAGE;

        $theme = theme_config::load('purity');

        if ($theme->settings->googlefonturl) {
            $url = $theme->settings->googlefonturl;
        } else {
            $url = false;
        }

        return $url;
    }

    public function header() {
        global $USER, $CFG;

        $theme = theme_config::load('purity');

        $course_id = $this->page->course->id;
        $context = context_course::instance($course_id);

        if ($this->page->course->format != 'site' && is_enrolled($context, $USER, '', false)) {
          $this->page->add_body_class('is-enrolled');
        }

        if (isset($USER->username) && $USER->username != 'guest') {
            $this->page->add_body_class('real-loggedin');
        }

        if ($theme->settings->showsectioncompletionicon == 'show') {
          $this->page->add_body_class('show-completion-icons');
        }

        if ($CFG->branch >= 400) {
          $this->page->add_body_class('moodle-v4');
        }

        if ($theme->settings->layout == 'modern') {
          $this->page->add_body_class('layout-modern');
          $this->page->add_body_class('course-index-enabled');
        } else {
          $this->page->add_body_class('layout-classic');
        }

        if ($theme->settings->swatch == 'flat') {
          $this->page->add_body_class('swatch-flat');
        } else {
          $this->page->add_body_class('swatch-default');
        }

        if ($theme->settings->showcourseindex == 'show') {
          $this->page->add_body_class('course-index-enabled');
        }
    
        return parent::header();
    }

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        $output = parent::standard_head_html();

        // Add google analytics code.
        $googleanalyticscode = "<script>
                                    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};
                                    ga.l=+new Date;ga('create', 'GOOGLE-ANALYTICS-CODE', 'auto');
                                    ga('send', 'pageview');
                                </script>
                                <script async src='https://www.google-analytics.com/analytics.js'></script>";

        $theme = theme_config::load('purity');

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        return $output;
    }

    /**
     * Returns a search box.
     *
     * @param  string $id     The search box wrapper div id, defaults to an autogenerated one.
     * @return string         HTML with the search form hidden by default.
     */
    public function search_box($id = false) {
        global $CFG;

        // Accessing $CFG directly as using \core_search::is_global_search_enabled would
        // result in an extra included file for each site, even the ones where global search
        // is disabled.
        if (empty($CFG->enableglobalsearch) || !has_capability('moodle/search:query', context_system::instance())) {
            return '';
        }

        if ($id == false) {
            $id = uniqid();
        } else {
            // Needs to be cleaned, we use it for the input id.
            $id = clean_param($id, PARAM_ALPHANUMEXT);
        }

        // JS to animate the form.
        // $this->page->requires->js_call_amd('core/search-input', 'init', array($id));

        $searchicon = html_writer::tag('div', $this->pix_icon('a/search', get_string('search', 'search'), 'moodle'),
            array('id' => 'searchDropdown', 'class' => 'nav-link', 'data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'aria-expanded' => 'false', 'role' => 'button', 'tabindex' => 0));
        $formattrs = array('class' => 'search-input-form-purity', 'action' => $CFG->wwwroot . '/search/index.php');
        $inputattrs = array('type' => 'text', 'name' => 'q', 'placeholder' => get_string('search', 'search'),
            'size' => 13, 'tabindex' => -1, 'id' => 'id_q_' . $id, 'class' => 'form-control');

        $contents = html_writer::tag('label', get_string('enteryoursearchquery', 'search'),
            array('for' => 'id_q_' . $id, 'class' => 'accesshide')) . html_writer::empty_tag('input', $inputattrs);
        if ($this->page->context && $this->page->context->contextlevel !== CONTEXT_SYSTEM) {
            $contents .= html_writer::empty_tag('input', ['type' => 'hidden',
                    'name' => 'context', 'value' => $this->page->context->id]);
        }
        $searchinputform = html_writer::tag('form', $contents, $formattrs);
        $searchinput = html_writer::tag('div', $searchinputform, array('class' => 'dropdown-menu dropdown-menu-right search-dropdown-purity', 'aria-labelledby' => 'searchDropdown'));

        return html_writer::tag('div', $searchicon . $searchinput, array('class' => 'dropdown', 'id' => $id));
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE, $PAGE;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $context->sitename = format_string($SITE->fullname, true,
                ['context' => context_course::instance(SITEID), "escape" => false]);

        if (isset($PAGE->theme->settings->loginpageshowlogo)) {
            if ($PAGE->theme->settings->loginpageshowlogo == 'yes') {
                $context->show_login_page_logo = true;
            } else {
                $context->show_login_page_logo = false;
            }
        }

        if (isset($PAGE->theme->settings->loginpagelogo)) {
            $context->loginpage_logo_url = $PAGE->theme->setting_file_url('loginpagelogo', 'loginpagelogo');
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Render the login signup form into a nice template for the theme.
     *
     * @param mform $form
     * @return string
     */
    public function render_login_signup_form($form) {
        global $SITE, $PAGE;

        $context = $form->export_for_template($this);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context['logourl'] = $url;
        $context['sitename'] = format_string($SITE->fullname, true,
                ['context' => context_course::instance(SITEID), "escape" => false]);

        if (isset($PAGE->theme->settings->loginpageshowlogo)) {
            if ($PAGE->theme->settings->loginpageshowlogo == 'yes') {
                $context['show_login_page_logo'] = true;
            } else {
                $context['show_login_page_logo'] = false;
            }
        }

        if (isset($PAGE->theme->settings->loginpagelogo)) {
            $context['loginpage_logo_url'] = $PAGE->theme->setting_file_url('loginpagelogo', 'loginpagelogo');
        }

        return $this->render_from_template('core/signup_form_layout', $context);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            // $returnstr = get_string('loggedinnot', 'moodle');
            $returnstr = '';
            if (!$loginpage) {
                $returnstr .= "<a class=\"btn btn-primary btn-sm\" href=\"$loginurl\">" . get_string('login') . '</a>';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );

        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = "<span class=\"login-text\">" . get_string('loggedinasguest') . '</span>';
            if (!$loginpage && $withlinks) {
                $returnstr .= "<a class=\"btn btn-primary btn-sm\" href=\"$loginurl\">".get_string('login').'</a>';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext mr-1') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, '', null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }

    // Get Frontpage Container class
    public function get_footer_container_class() {
        global $PAGE;

        $theme = theme_config::load('purity');

        if ($theme->settings->pageasfrontpage == 'yes') {
            $is_frontpage = $PAGE->bodyid == 'page-site-index' || $PAGE->bodyid == 'page-mod-page-view';
        } else {
            $is_frontpage = $PAGE->bodyid == 'page-site-index';
        }

        if ($theme->settings->containerwidth == 'fixed' && $is_frontpage) {
            $footer_container_class = 'container';
        } else {
            $footer_container_class = 'container-fluid';
        }

        return $footer_container_class;
    }

    /**
     * Renders a custom menu object (located in outputcomponents.php)
     *
     * The custom menu this method produces makes use of the YUI3 menunav widget
     * and requires very specific html elements and classes.
     *
     * @staticvar int $menucount
     * @param custom_menu $menu
     * @return string
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        $theme = theme_config::load('purity');

        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';

        if (!$menu->has_children() && !$haslangmenu) {
            return '';
        }

        if ($haslangmenu && $theme->settings->langmenulocation == 'custom') {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /**
     * We want to show the custom menus as a list of links in the footer on small screens.
     * Just return the menu object exported so we can render it differently.
     */
    public function custom_menu_flat() {
        global $CFG;
        $custommenuitems = '';

        $theme = theme_config::load('purity');

        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';

        if ($haslangmenu && $theme->settings->langmenulocation == 'custom') {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $custommenu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        return $custommenu->export_for_template($this);
    }

    /**
     * Renders the a standalone lang menu
     *
     * @return mixed
     */
    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu;

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }

            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            if (isset($context)) {
                return $this->render_from_template('theme_purity/lang_menu', $context);
            }
        }
    }

    /**
     * See if this is the first view of the current cm in the session if it has fake blocks.
     *
     * (We track up to 100 cms so as not to overflow the session.)
     * This is done for drawer regions containing fake blocks so we can show blocks automatically.
     *
     * @return boolean true if the page has fakeblocks and this is the first visit.
     */
    public function firstview_fakeblocks(): bool {
        global $SESSION;

        $firstview = false;
        if ($this->page->cm) {
            if (!$this->page->blocks->region_has_fakeblocks('side-pre')) {
                return false;
            }
            if (!property_exists($SESSION, 'firstview_fakeblocks')) {
                $SESSION->firstview_fakeblocks = [];
            }
            if (array_key_exists($this->page->cm->id, $SESSION->firstview_fakeblocks)) {
                $firstview = false;
            } else {
                $SESSION->firstview_fakeblocks[$this->page->cm->id] = true;
                $firstview = true;
                if (count($SESSION->firstview_fakeblocks) > 100) {
                    array_shift($SESSION->firstview_fakeblocks);
                }
            }
        }
        return $firstview;
    }

    /**
     *
     * Outputs the course progress if course completion is on.
     *
     * @return string Markup.
     */
    protected function courseprogress($course) {
      global $USER;
      $theme = \theme_config::load('purity');

      $output = '';
      $courseformat = course_get_format($course);

      if (get_class($courseformat) != 'format_tiles') {
          $completion = new \completion_info($course);

          // Start Course progress count.
          // Make sure we continue with a valid userid.
          if (empty($userid)) {
              $userid = $USER->id;
          }
          $completion = new \completion_info($course);

          // Get the number of modules that support completion.
          $modules = $completion->get_activities();
          $count = count($modules);
          if (!$count) {
              return null;
          }

          // Get the number of modules that have been completed.
          $completed = 0;
          foreach ($modules as $module) {
              $data = $completion->get_data($module, true, $userid);
              $completed += $data->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
          }
          $progresscountc = $completed;
          $progresscounttotal = $count;
          // End progress count.

          if ($completion->is_enabled()) {
              $templatedata = new \stdClass;
              $templatedata->progress = \core_completion\progress::get_course_progress_percentage($course);
              $templatedata->progresscountc = $progresscountc;
              $templatedata->progresscounttotal = $progresscounttotal;

              if (!is_null($templatedata->progress)) {
                  $templatedata->progress = floor($templatedata->progress);
              } else {
                  $templatedata->progress = 0;
              }

              if ($theme->settings->courseprogressbar == 'show') {
                  $progressbar = $this->render_from_template('theme_purity/progress-bar', $templatedata);
                  
                  if (has_capability('report/progress:view',  \context_course::instance($course->id))) {
                      $courseprogress = new \moodle_url('/report/progress/index.php');
                      $courseprogress->param('course', $course->id);
                      $courseprogress->param('sesskey', sesskey());
                      $output .= html_writer::link($courseprogress, $progressbar, array('class' => 'course-progressbar-link'));
                  } else {
                      $output .= $progressbar;
                  }
              }
          }
      }

      return $output;
    }

    public function display_course_progress() {
        $html = null;
        $html .= $this->courseprogress($this->page->course);
        return $html;
    }

}