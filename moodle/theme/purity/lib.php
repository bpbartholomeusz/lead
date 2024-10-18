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
 
// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// Get the main SCSS content.
function theme_purity_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $scss .= file_get_contents($CFG->dirroot . '/theme/purity/scss/main.scss');
    $customscss = file_get_contents($CFG->dirroot . '/theme/purity/scss/custom.scss');

    // Conditional example for future use.
    // if ($theme->settings->pagelayout == 1) {
    //     $scss .= file_get_contents($CFG->dirroot . '/theme/fordson/scss/pagelayout/layout1.scss');
    // }
    // if ($theme->settings->pagelayout == 2) {
    //     $scss .= file_get_contents($CFG->dirroot . '/theme/fordson/scss/pagelayout/layout2.scss');
    // }

    return $scss . "\n" . $customscss;
}

// Get SCSS to prepend.
function theme_purity_scss_to_prepend($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'primarycolor' => ['purity-primary-color'],
        'secondarycolor' => ['purity-secondary-color'],
        'bodybgcolor' => ['purity-body-bg-color'],
        'gray100' => ['purity-gray-100'],
        'gray200' => ['purity-gray-200'],
        'gray300' => ['purity-gray-300'],
        'gray400' => ['purity-gray-400'],
        'gray500' => ['purity-gray-500'],
        'gray600' => ['purity-gray-600'],
        'gray700' => ['purity-gray-700'],
        'gray800' => ['purity-gray-800'],
        'gray900' => ['purity-gray-900'],
        'bordercolor' => ['purity-border-color'],
        'pagetopbgcolor' => ['purity-page-top-bg-color'],
        'pagetoptextcolor' => ['purity-page-top-text-color'],
        'bodyfontfamily' => ['purity-body-font-family'],
        'headingsfontfamily' => ['purity-headings-font-family'],
        'bodyfontsize' => ['purity-body-font-size'],
        'h1fontsize' => ['purity-h1-font-size'],
        'h2fontsize' => ['purity-h2-font-size'],
        'h3fontsize' => ['purity-h3-font-size'],
        'h4fontsize' => ['purity-h4-font-size'],
        'h5fontsize' => ['purity-h5-font-size'],
        'h6fontsize' => ['purity-h6-font-size'],
        'fontweightlight' => ['purity-font-weight-light'],
        'fontweightnormal' => ['purity-font-weight-normal'],
        'fontweightbold' => ['purity-font-weight-bold'],
        'fontweightextrabold' => ['purity-font-weight-extra-bold'],
        'menubreakpoint' => ['purity-menu-breakpoint'],
        'fpfullwidthbgcolor' => ['fp-fullwidth-bg-color'],
        'fpfullwidthbgrepeat' => ['fp-fullwidth-bg-repeat'],
        'fpfullwidthbgsize' => ['fp-fullwidth-bg-size'],
        'fpfullwidthbgposition' => ['fp-fullwidth-bg-position'],
        'fpfullwidthbgattachment' => ['fp-fullwidth-bg-attachment'],
        'fpfullwidthtextcolor' => ['fp-fullwidth-text-color'],
        'fpfullwidthheadingcolor' => ['fp-fullwidth-heading-color'],
        'fpintrobgcolor' => ['fp-intro-bg-color'],
        'fpintrobgrepeat' => ['fp-intro-bg-repeat'],
        'fpintrobgsize' => ['fp-intro-bg-size'],
        'fpintrobgposition' => ['fp-intro-bg-position'],
        'fpintrobgattachment' => ['fp-intro-bg-attachment'],
        'fpintrotextcolor' => ['fp-intro-text-color'],
        'fpintroheadingcolor' => ['fp-intro-heading-color'],
        'fpfeaturebgcolor' => ['fp-feature-bg-color'],
        'fpfeaturebgrepeat' => ['fp-feature-bg-repeat'],
        'fpfeaturebgsize' => ['fp-feature-bg-size'],
        'fpfeaturebgposition' => ['fp-feature-bg-position'],
        'fpfeaturebgattachment' => ['fp-feature-bg-attachment'],
        'fpfeaturetextcolor' => ['fp-feature-text-color'],
        'fpfeatureheadingcolor' => ['fp-feature-heading-color'],
        'fputilitybgcolor' => ['fp-utility-bg-color'],
        'fputilitybgrepeat' => ['fp-utility-bg-repeat'],
        'fputilitybgsize' => ['fp-utility-bg-size'],
        'fputilitybgposition' => ['fp-utility-bg-position'],
        'fputilitybgattachment' => ['fp-utility-bg-attachment'],
        'fputilitytextcolor' => ['fp-utility-text-color'],
        'fputilityheadingcolor' => ['fp-utility-heading-color'],
        'fpextensionbgcolor' => ['fp-extension-bg-color'],
        'fpextensionbgrepeat' => ['fp-extension-bg-repeat'],
        'fpextensionbgsize' => ['fp-extension-bg-size'],
        'fpextensionbgposition' => ['fp-extension-bg-position'],
        'fpextensionbgattachment' => ['fp-extension-bg-attachment'],
        'fpextensiontextcolor' => ['fp-extension-text-color'],
        'fpextensionheadingcolor' => ['fp-extension-heading-color'],
        'fpadditionalbgcolor' => ['fp-additional-bg-color'],
        'fpadditionalbgrepeat' => ['fp-additional-bg-repeat'],
        'fpadditionalbgsize' => ['fp-additional-bg-size'],
        'fpadditionalbgposition' => ['fp-additional-bg-position'],
        'fpadditionalbgattachment' => ['fp-additional-bg-attachment'],
        'fpadditionaltextcolor' => ['fp-additional-text-color'],
        'fpadditionalheadingcolor' => ['fp-additional-heading-color'],
        'fpprebottombgcolor' => ['fp-prebottom-bg-color'],
        'fpprebottombgrepeat' => ['fp-prebottom-bg-repeat'],
        'fpprebottombgsize' => ['fp-prebottom-bg-size'],
        'fpprebottombgposition' => ['fp-prebottom-bg-position'],
        'fpprebottombgattachment' => ['fp-prebottom-bg-attachment'],
        'fpprebottomtextcolor' => ['fp-prebottom-text-color'],
        'fpprebottomheadingcolor' => ['fp-prebottom-heading-color'],
        'fpbottombgcolor' => ['fp-bottom-bg-color'],
        'fpbottombgrepeat' => ['fp-bottom-bg-repeat'],
        'fpbottombgsize' => ['fp-bottom-bg-size'],
        'fpbottombgposition' => ['fp-bottom-bg-position'],
        'fpbottombgattachment' => ['fp-bottom-bg-attachment'],
        'fpbottomtextcolor' => ['fp-bottom-text-color'],
        'fpbottomheadingcolor' => ['fp-bottom-heading-color'],
        'fpafterbottombgcolor' => ['fp-afterbottom-bg-color'],
        'fpafterbottombgrepeat' => ['fp-afterbottom-bg-repeat'],
        'fpafterbottombgsize' => ['fp-afterbottom-bg-size'],
        'fpafterbottombgposition' => ['fp-afterbottom-bg-position'],
        'fpafterbottombgattachment' => ['fp-afterbottom-bg-attachment'],
        'fpafterbottomtextcolor' => ['fp-afterbottom-text-color'],
        'fpafterbottomheadingcolor' => ['fp-afterbottom-heading-color'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

// Get SCSS to append.
function theme_purity_scss_to_append($theme) {
    return !empty($theme->settings->scss) ? $theme->settings->scss : '';
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_purity_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
  if ($context->contextlevel == CONTEXT_SYSTEM) {
      $theme = theme_config::load('purity');
      if ($filearea === 'headerlogo') {
          return $theme->setting_file_serve('headerlogo', $args, $forcedownload, $options);
      } else if ($filearea === 'navdrawerlogo') {
          return $theme->setting_file_serve('navdrawerlogo', $args, $forcedownload, $options);
      } else if ($filearea === 'loginpagelogo') {
          return $theme->setting_file_serve('loginpagelogo', $args, $forcedownload, $options);
      } else if ($filearea === 'loginpagebgimage') {
          return $theme->setting_file_serve('loginpagebgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpfullwidthbgimage') {
          return $theme->setting_file_serve('fpfullwidthbgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpintrobgimage') {
          return $theme->setting_file_serve('fpintrobgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpfeaturebgimage') {
          return $theme->setting_file_serve('fpfeaturebgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fputilitybgimage') {
          return $theme->setting_file_serve('fputilitybgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpextensionbgimage') {
          return $theme->setting_file_serve('fpextensionbgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpadditionalbgimage') {
          return $theme->setting_file_serve('fpadditionalbgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpprebottombgimage') {
          return $theme->setting_file_serve('fpprebottombgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpbottombgimage') {
          return $theme->setting_file_serve('fpbottombgimage', $args, $forcedownload, $options);
      } else if ($filearea === 'fpafterbottombgimage') {
          return $theme->setting_file_serve('fpafterbottombgimage', $args, $forcedownload, $options);
      } else {
          send_file_not_found();
      }
  } else {
      send_file_not_found();
  }
}

/**
 * Extend theme navigation
 * Author: Willian Mano Araújo
 * https://moodle.org/plugins/theme_moove
 * @param flat_navigation $flatnav
 */
function theme_purity_extend_flat_navigation(\flat_navigation $flatnav) {
  theme_purity_rebuildcoursesections($flatnav);
  theme_purity_delete_menuitems($flatnav);
}

/**
 * Remove items from navigation
 * Author: Willian Mano Araújo
 * https://moodle.org/plugins/theme_moove
 * @param flat_navigation $flatnav
 */
function theme_purity_delete_menuitems(\flat_navigation $flatnav) {

  foreach ($flatnav as $item) {

      $itemstodelete = [];

      if (in_array($item->key, $itemstodelete)) {
          $flatnav->remove($item->key);

          continue;
      }

      if (is_numeric($item->key)) {

          $flatnav->remove($item->key);

          continue;
      }

      if (isset($item->parent->key) && $item->parent->key == 'mycourses' &&
          isset($item->type) && $item->type == \navigation_node::TYPE_COURSE) {

          $flatnav->remove($item->key);

          continue;
      }

  }
}

/**
 * Improve flat navigation menu
 *
 * @param flat_navigation $flatnav
 */
function theme_purity_rebuildcoursesections(\flat_navigation $flatnav) {
    global $PAGE;

    if (!isguestuser() ) {
        
        $participantsitem = $flatnav->find('participants', \navigation_node::TYPE_CONTAINER);

        if (!$participantsitem) {
            return;
        }
    
        if ($PAGE->course->format != 'singleactivity' && $PAGE->course->format != 'social') {
            $coursesectionsoptions = [
                'text' => get_string('coursesections', 'theme_purity'),
                'shorttext' => get_string('coursesections', 'theme_purity'),
                'icon' => new pix_icon('t/viewdetails', ''),
                'type' => \navigation_node::COURSE_CURRENT,
                'key' => 'course-sections',
                'parent' => $participantsitem->parent
            ];

            $coursesections = new \flat_navigation_node($coursesectionsoptions, 0);

            foreach ($flatnav as $item) {
                if ($item->type == \navigation_node::TYPE_SECTION) {
                    $coursesections->add_node(new \navigation_node([
                        'text' => $item->text,
                        'shorttext' => $item->shorttext,
                        'icon' => $item->icon,
                        'type' => $item->type,
                        'key' => $item->key,
                        'parent' => $coursesections,
                        'action' => $item->action
                    ]));
                }
            }

            $flatnav->add($coursesections, 'myhome');

        }

    }
}
