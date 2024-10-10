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
 * A drawer based layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

$moodleVersion = $CFG->branch;

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
  $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
  $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
  $courseindexopen = false;
  $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
  $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
  $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
  $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
  $courseindexopen = false;
}

$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();


// Layout
$layout_classic = false;
$layout_modern = false;
if (get_config('theme_purity', 'layout') == 'modern') {
  $layout_modern = true;
} else {
  $layout_classic = true;
}

// Custom block regions
$blocks_fp_fullwidth = $OUTPUT->blocks('fp-fullwidth');
$has_blocks_fp_fullwidth = (strpos($blocks_fp_fullwidth, 'data-block=') !== false);

$blocks_fp_intro = $OUTPUT->blocks('fp-intro');
$has_blocks_fp_intro = (strpos($blocks_fp_intro, 'data-block=') !== false);

$blocks_fp_feature = $OUTPUT->blocks('fp-feature');
$has_blocks_fp_feature = (strpos($blocks_fp_feature, 'data-block=') !== false);

$blocks_fp_utility = $OUTPUT->blocks('fp-utility');
$has_blocks_fp_utility = (strpos($blocks_fp_utility, 'data-block=') !== false);

$blocks_fp_extension = $OUTPUT->blocks('fp-extension');
$has_blocks_fp_extension = (strpos($blocks_fp_extension, 'data-block=') !== false);

$blocks_fp_additional = $OUTPUT->blocks('fp-additional');
$has_blocks_fp_additional = (strpos($blocks_fp_additional, 'data-block=') !== false);

$blocks_fp_prebottom = $OUTPUT->blocks('fp-prebottom');
$has_blocks_fp_prebottom = (strpos($blocks_fp_prebottom, 'data-block=') !== false);

$blocks_fp_bottom = $OUTPUT->blocks('fp-bottom');
$has_blocks_fp_bottom = (strpos($blocks_fp_bottom, 'data-block=') !== false);

$blocks_fp_afterbottom = $OUTPUT->blocks('fp-afterbottom');
$has_blocks_fp_afterbottom = (strpos($blocks_fp_afterbottom, 'data-block=') !== false);

$blocks_main_top = $OUTPUT->blocks('main-top');
$has_blocks_main_top = (strpos($blocks_main_top, 'data-block=') !== false);

$blocks_above_content = $OUTPUT->blocks('above-content');
$has_blocks_above_content = (strpos($blocks_above_content, 'data-block=') !== false);

$blocks_below_content = $OUTPUT->blocks('below-content');
$has_blocks_below_content = (strpos($blocks_below_content, 'data-block=') !== false);

$blocks_main_bottom = $OUTPUT->blocks('main-bottom');
$has_blocks_main_bottom = (strpos($blocks_main_bottom, 'data-block=') !== false);

// Custom Layout Options
$hascard = (empty($PAGE->layout_options['nocard']));

// Header Style
$header_style1 = false;
$header_style2 = false;
$header_style3 = false;
if (get_config('theme_purity', 'headerstyle') == 'style2') {
  $header_style2 = true;
  $extraclasses[] = 'header-style2';
} else if (get_config('theme_purity', 'headerstyle') == 'style3') {
  $header_style3 = true;
  $extraclasses[] = 'header-style3';
} else {
  $header_style1 = true;
  $extraclasses[] = 'header-style1';
}

// Header Style Class
$header_style_class = get_config('theme_purity', 'headerstyle');

// Topbar Left Content
$topbar_left_content = format_text(get_config('theme_purity', 'topbarleftcontent'), FORMAT_HTML, array('filter' => true));

// Topbar Right Content
$topbar_right_content = format_text(get_config('theme_purity', 'topbarrightcontent'), FORMAT_HTML, array('filter' => true));

// Header Color
$header_dark = false;
$header_light = false;
if (get_config('theme_purity', 'headercolor') == 'dark') {
  $header_dark = true;
} else {
  $header_light = true;
}

// Header Logo URL
$header_logo_url = '';
if ($OUTPUT->get_filearea_image_url('headerlogo')) {
  $header_logo_url = $OUTPUT->get_filearea_image_url('headerlogo');
} else if ($OUTPUT->get_compact_logo_url()) {
  $header_logo_url = $OUTPUT->get_compact_logo_url();
}

// Header Site Name
if (get_config('theme_purity', 'headershowsitename') == 'yes') {
  $show_header_sitename = true;
}  else {
  $show_header_sitename = false;
}

// Header Logo
if (get_config('theme_purity', 'headershowlogo') == 'yes') {
  $show_header_logo = true;
}  else {
  $show_header_logo = false;
}

// Header Navbar-Brand
if ($show_header_logo || $show_header_sitename) {
  $show_header_navbar_brand = true;
}  else {
  $show_header_navbar_brand = false;
}

// Show Language Menu
$haslangmenu = $this->lang_menu() != '';
if ($haslangmenu && get_config('theme_purity', 'langmenulocation') == 'plugin') {
  $show_lang_menu = true;
}  else {
  $show_lang_menu = false;
}

// Footer Color
$footer_dark = false;
$footer_light = false;
if (get_config('theme_purity', 'footercolor') == 'dark') {
  $footer_dark = true;
} else {
  $footer_light = true;
}

// Has Page Footer
$has_page_footer = false;
if (get_config('theme_purity', 'footercontent') || get_config('theme_purity', 'footercopyrightcontent') || get_config('theme_purity', 'footersocialicons')) {
  $has_page_footer = true;
}

// Has Footer Bottom
$has_footer_bottom = false;
if (get_config('theme_purity', 'footercopyrightcontent') || get_config('theme_purity', 'footersocialicons')) {
  $has_footer_bottom = true;
}

// Footer Content
$footer_content = format_text(get_config('theme_purity', 'footercontent'), FORMAT_HTML, array('filter' => true));

// Footer Copyright Content
$footer_copyright_content = format_text(get_config('theme_purity', 'footercopyrightcontent'), FORMAT_HTML, array('filter' => true));

// Footer Social Icons
$footer_social_icons = format_text(get_config('theme_purity', 'footersocialicons'), FORMAT_HTML, array('filter' => true));

// Page Top
$hasnavbar = empty($PAGE->layout_options['nonavbar']);

// Show Course Content Title
$show_course_content_title = ($PAGE->bodyid == 'page-course-view-topics' || $PAGE->bodyid == 'page-course-view-weeks' || $PAGE->bodyid == 'page-course-view-social') && !strpos($PAGE->bodyclasses, 'path-user');

// Is Course Participants
$is_course_participants = strpos($PAGE->bodyclasses, 'path-user') && strpos($PAGE->bodyclasses, 'path-course');

// Is Frontpage
if (get_config('theme_purity', 'pageasfrontpage') == 'yes') {
  $is_frontpage = $PAGE->bodyid == 'page-site-index' || $PAGE->bodyid == 'page-mod-page-view';
}  else {
  $is_frontpage = $PAGE->bodyid == 'page-site-index';
}

// Is Real Frontpage
$is_real_frontpage = $PAGE->bodyid == 'page-site-index';

// Frontpage Container Class
if (get_config('theme_purity', 'containerwidth') == 'fixed' && $is_frontpage) {
  $container_class = 'container';
}  else {
  $container_class = 'container-fluid';
}

// Show Breadcrumbs on Frontpage and Page
if ((get_config('theme_purity', 'showbreadcrumbs') == 'hide') && ($PAGE->bodyid == 'page-site-index' || $PAGE->bodyid == 'page-mod-page-view')) {
  $show_breadcrumbs = false;
}  else if (get_config('theme_purity', 'showbreadcrumbs') == 'show-page' && $PAGE->bodyid == 'page-site-index') {
  $show_breadcrumbs = false;
} else {
  $show_breadcrumbs = true;
}

// Show Main Content on Frontpage and Page
if ((get_config('theme_purity', 'showmaincontent') == 'hide') && ($PAGE->bodyid == 'page-site-index' || $PAGE->bodyid == 'page-mod-page-view')) {
  $show_main_content = false;
}  else if (get_config('theme_purity', 'showmaincontent') == 'show-page' && $PAGE->bodyid == 'page-site-index') {
  $show_main_content = false;
} else {
  $show_main_content = true;
}

// Fullwidth Section Background Image
if ($OUTPUT->get_filearea_image_url('fpfullwidthbgimage')) {
  $fullwidth_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpfullwidthbgimage');

  $fpfullwidth_styles = 'background-image: url(' . $fullwidth_section_bg_image_url . ');';
} else {
  $fpfullwidth_styles = '';
}

// Intro Section Background Image
if ($OUTPUT->get_filearea_image_url('fpintrobgimage')) {
  $intro_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpintrobgimage');

  $fpintro_styles = 'background-image: url(' . $intro_section_bg_image_url . ');';
} else {
  $fpintro_styles = '';
}

// Feature Section Background Image
if ($OUTPUT->get_filearea_image_url('fpfeaturebgimage')) {
  $feature_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpfeaturebgimage');

  $fpfeature_styles = 'background-image: url(' . $feature_section_bg_image_url . ');';
} else {
  $fpfeature_styles = '';
}

// Utility Section Background Image
if ($OUTPUT->get_filearea_image_url('fputilitybgimage')) {
  $utility_section_bg_image_url = $OUTPUT->get_filearea_image_url('fputilitybgimage');

  $fputility_styles = 'background-image: url(' . $utility_section_bg_image_url . ');';
} else {
  $fputility_styles = '';
}

// Extension Section Background Image
if ($OUTPUT->get_filearea_image_url('fpextensionbgimage')) {
  $extension_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpextensionbgimage');

  $fpextension_styles = 'background-image: url(' . $extension_section_bg_image_url . ');';
} else {
  $fpextension_styles = '';
}

// Additional Section Background Image
if ($OUTPUT->get_filearea_image_url('fpadditionalbgimage')) {
  $additional_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpadditionalbgimage');

  $fpadditional_styles = 'background-image: url(' . $additional_section_bg_image_url . ');';
} else {
  $fpadditional_styles = '';
}

// Prebottom Section Background Image
if ($OUTPUT->get_filearea_image_url('fpprebottombgimage')) {
  $prebottom_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpprebottombgimage');

  $fpprebottom_styles = 'background-image: url(' . $prebottom_section_bg_image_url . ');';
} else {
  $fpprebottom_styles = '';
}

// Bottom Section Background Image
if ($OUTPUT->get_filearea_image_url('fpbottombgimage')) {
  $bottom_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpbottombgimage');

  $fpbottom_styles = 'background-image: url(' . $bottom_section_bg_image_url . ');';
} else {
  $fpbottom_styles = '';
}

// Afterbottom Section Background Image
if ($OUTPUT->get_filearea_image_url('fpafterbottombgimage')) {
  $afterbottom_section_bg_image_url = $OUTPUT->get_filearea_image_url('fpafterbottombgimage');

  $fpafterbottom_styles = 'background-image: url(' . $afterbottom_section_bg_image_url . ');';
} else {
  $fpafterbottom_styles = '';
}


$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
  $secondary = $PAGE->secondarynav;

  if ($secondary->get_children_key_list()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $extraclasses[] = 'has-secondarynavigation';
  }

  $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
  if (!is_null($overflowdata)) {
    $overflow = $overflowdata->export_for_template($OUTPUT);
  }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$templatecontext = [
  'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
  'output' => $OUTPUT,
  'sidepreblocks' => $blockshtml,
  'hasblocks' => $hasblocks,
  'blocks_fp_fullwidth' => $blocks_fp_fullwidth,
  'has_blocks_fp_fullwidth' => $has_blocks_fp_fullwidth,
  'blocks_fp_intro' => $blocks_fp_intro,
  'has_blocks_fp_intro' => $has_blocks_fp_intro,
  'blocks_fp_feature' => $blocks_fp_feature,
  'has_blocks_fp_feature' => $has_blocks_fp_feature,
  'blocks_fp_utility' => $blocks_fp_utility,
  'has_blocks_fp_utility' => $has_blocks_fp_utility,
  'blocks_fp_extension' => $blocks_fp_extension,
  'has_blocks_fp_extension' => $has_blocks_fp_extension,
  'blocks_fp_additional' => $blocks_fp_additional,
  'has_blocks_fp_additional' => $has_blocks_fp_additional,
  'blocks_fp_prebottom' => $blocks_fp_prebottom,
  'has_blocks_fp_prebottom' => $has_blocks_fp_prebottom,
  'blocks_fp_bottom' => $blocks_fp_bottom,
  'has_blocks_fp_bottom' => $has_blocks_fp_bottom,
  'blocks_fp_afterbottom' => $blocks_fp_afterbottom,
  'has_blocks_fp_afterbottom' => $has_blocks_fp_afterbottom,
  'blocks_main_top' => $blocks_main_top,
  'has_blocks_main_top' => $has_blocks_main_top,
  'blocks_above_content' => $blocks_above_content,
  'has_blocks_above_content' => $has_blocks_above_content,
  'blocks_below_content' => $blocks_below_content,
  'has_blocks_below_content' => $has_blocks_below_content,
  'blocks_main_bottom' => $blocks_main_bottom,
  'has_blocks_main_bottom' => $has_blocks_main_bottom,
  'hascard' => $hascard,
  'bodyattributes' => $bodyattributes,
  'courseindexopen' => $courseindexopen,
  'blockdraweropen' => $blockdraweropen,
  'courseindex' => $courseindex,
  'primarymoremenu' => $primarymenu['moremenu'],
  'secondarymoremenu' => $secondarynavigation ?: false,
  'mobileprimarynav' => $primarymenu['mobileprimarynav'],
  'usermenu' => $primarymenu['user'],
  'langmenu' => $primarymenu['lang'],
  'forceblockdraweropen' => $forceblockdraweropen,
  'regionmainsettingsmenu' => $regionmainsettingsmenu,
  'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
  'overflow' => $overflow,
  'headercontent' => $headercontent,
  'addblockbutton' => $addblockbutton,
  'header_style1' => $header_style1,
  'header_style2' => $header_style2,
  'header_style3' => $header_style3,
  'header_style_class' => $header_style_class,
  'topbar_left_content' => $topbar_left_content,
  'topbar_right_content' => $topbar_right_content,
  'header_light' => $header_light,
  'header_dark' => $header_dark,
  'header_logo_url' => $header_logo_url,
  'show_header_sitename' => $show_header_sitename,
  'show_header_logo' => $show_header_logo,
  'show_header_navbar_brand' => $show_header_navbar_brand,
  'show_lang_menu' => $show_lang_menu,
  'hasnavbar' => $hasnavbar,
  'footer_light' => $footer_light,
  'footer_dark' => $footer_dark,
  'footer_content' => $footer_content,
  'footer_copyright_content' => $footer_copyright_content,
  'footer_social_icons' => $footer_social_icons,
  'has_page_footer' => $has_page_footer,
  'has_footer_bottom' => $has_footer_bottom,
  'show_course_content_title' => $show_course_content_title,
  'is_course_participants' => $is_course_participants,
  'is_frontpage' => $is_frontpage,
  'container_class' => $container_class,
  'show_breadcrumbs' => $show_breadcrumbs,
  'show_main_content' => $show_main_content,
  'is_real_frontpage' => $is_real_frontpage,
  'fpfullwidth_styles' => $fpfullwidth_styles,
  'fpintro_styles' => $fpintro_styles,
  'fpfeature_styles' => $fpfeature_styles,
  'fputility_styles' => $fputility_styles,
  'fpextension_styles' => $fpextension_styles,
  'fpadditional_styles' => $fpadditional_styles,
  'fpprebottom_styles' => $fpprebottom_styles,
  'fpbottom_styles' => $fpbottom_styles,
  'fpafterbottom_styles' => $fpafterbottom_styles,
  'is_moodle4' => $moodleVersion >= 400 ? true : false,
  'layout_classic' => $layout_classic,
  'layout_modern' => $layout_modern
];

$PAGE->requires->jquery();

echo $OUTPUT->render_from_template('theme_purity/drawers', $templatecontext);
