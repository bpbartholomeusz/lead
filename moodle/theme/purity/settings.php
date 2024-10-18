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

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when                      
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingpurity', get_string('configtitle', 'theme_purity'));

    // Each page is a tab
    // "General" tab.
    $page = new admin_settingpage('theme_purity_general', get_string('general_settings', 'theme_purity'));

    // Layout
    $name = 'theme_purity/layout';
    $title = get_string('layout', 'theme_purity');
    $description = get_string('layout_desc', 'theme_purity');
    $default = 'classic';
    $options = array('classic' => 'Classic', 'modern' => 'Modern');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Swatch
    $name = 'theme_purity/swatch';
    $title = get_string('swatch', 'theme_purity');
    $description = get_string('swatch_desc', 'theme_purity');
    $default = 'default';
    $options = array('default' => 'Default', 'flat' => 'Flat');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Variable $purity-primary-color.
    $name = 'theme_purity/primarycolor';
    $title = get_string('primarycolor', 'theme_purity');
    $description = get_string('primarycolor_desc', 'theme_purity');
    $default = '#5e72e4';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-secondary-color.
    $name = 'theme_purity/secondarycolor';
    $title = get_string('secondarycolor', 'theme_purity');
    $description = get_string('secondarycolor_desc', 'theme_purity');
    $default = '#172b4d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-body-bg-color.
    $name = 'theme_purity/bodybgcolor';
    $title = get_string('bodybgcolor', 'theme_purity');
    $description = get_string('bodybgcolor_desc', 'theme_purity');
    $default = '#f8f9fe';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-100.
    $name = 'theme_purity/gray100';
    $title = get_string('gray100', 'theme_purity');
    $description = get_string('gray100_desc', 'theme_purity');
    $default = '#f6f9fc';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-200.
    $name = 'theme_purity/gray200';
    $title = get_string('gray200', 'theme_purity');
    $description = get_string('gray200_desc', 'theme_purity');
    $default = '#e9ecef';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-300.
    $name = 'theme_purity/gray300';
    $title = get_string('gray300', 'theme_purity');
    $description = get_string('gray300_desc', 'theme_purity');
    $default = '#dee2e6';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-400.
    $name = 'theme_purity/gray400';
    $title = get_string('gray400', 'theme_purity');
    $description = get_string('gray400_desc', 'theme_purity');
    $default = '#ced4da';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-500.
    $name = 'theme_purity/gray500';
    $title = get_string('gray500', 'theme_purity');
    $description = get_string('gray500_desc', 'theme_purity');
    $default = '#adb5bd';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-600.
    $name = 'theme_purity/gray600';
    $title = get_string('gray600', 'theme_purity');
    $description = get_string('gray600_desc', 'theme_purity');
    $default = '#8898aa';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-700.
    $name = 'theme_purity/gray700';
    $title = get_string('gray700', 'theme_purity');
    $description = get_string('gray700_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-800.
    $name = 'theme_purity/gray800';
    $title = get_string('gray800', 'theme_purity');
    $description = get_string('gray800_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-gray-900.
    $name = 'theme_purity/gray900';
    $title = get_string('gray900', 'theme_purity');
    $description = get_string('gray900_desc', 'theme_purity');
    $default = '#212529';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-border-color.
    $name = 'theme_purity/bordercolor';
    $title = get_string('bordercolor', 'theme_purity');
    $description = get_string('bordercolor_desc', 'theme_purity');
    $default = '#e9ecef';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-page-top-bg-color.
    $name = 'theme_purity/pagetopbgcolor';
    $title = get_string('pagetopbgcolor', 'theme_purity');
    $description = get_string('pagetopbgcolor_desc', 'theme_purity');
    $default = '#5e72e4';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $purity-page-top-text-color.
    $name = 'theme_purity/pagetoptextcolor';
    $title = get_string('pagetoptextcolor', 'theme_purity');
    $description = get_string('pagetoptextcolor_desc', 'theme_purity');
    $default = '#ffffff';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);


    // "Header" tab.
    $page = new admin_settingpage('theme_purity_header', get_string('header_settings', 'theme_purity'));

    // Header Styles.
    $name = 'theme_purity/headerstyle';
    $title = get_string('headerstyle', 'theme_purity');
    $description = get_string('headerstyle_desc', 'theme_purity');
    $default = 'style1';
    $options = array('style1' => 'Style 1', 'style2' => 'Style 2', 'style3' => 'Style 3');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Header Color Variation.
    $name = 'theme_purity/headercolor';
    $title = get_string('headercolor', 'theme_purity');
    $description = get_string('headercolor_desc', 'theme_purity');
    $default = 'light';
    $options = array('light' => 'Light', 'dark' => 'Dark');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show Header Logo.
    $name = 'theme_purity/headershowlogo';
    $title = get_string('headershowlogo', 'theme_purity');
    $description = get_string('headershowlogo_desc', 'theme_purity');
    $default = 'no';
    $options = array('yes' => 'Yes', 'no' => 'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Header Logo.
    $name= 'theme_purity/headerlogo';
    $title = get_string('headerlogo', 'theme_purity');
    $description = get_string('headerlogo_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'headerlogo');
    $page->add($setting);

    // Show Site Name.
    $name = 'theme_purity/headershowsitename';
    $title = get_string('headershowsitename', 'theme_purity');
    $description = get_string('headershowsitename_desc', 'theme_purity');
    $default = 'no';
    $options = array('yes' => 'Yes', 'no' => 'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Language Menu Location.
    $name = 'theme_purity/langmenulocation';
    $title = get_string('langmenulocation', 'theme_purity');
    $description = get_string('langmenulocation_desc', 'theme_purity');
    $default = 'plugin';
    $options = array('custom' => 'Custom Menu', 'plugin' => 'Plugin Menu');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Menu Breakpoint
    $name = 'theme_purity/menubreakpoint';
    $title = get_string('menubreakpoint', 'theme_purity');
    $description = get_string('menubreakpoint_desc', 'theme_purity');
    $default = '992px';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Topbar Left Content.
    $name = 'theme_purity/topbarleftcontent';
    $title = get_string('topbarleftcontent', 'theme_purity');
    $description = get_string('topbarleftcontent_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    // Topbar Right Content.
    $name = 'theme_purity/topbarrightcontent';
    $title = get_string('topbarrightcontent', 'theme_purity');
    $description = get_string('topbarrightcontent_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $settings->add($page);


    // "NavDrawer" tab.
    $page = new admin_settingpage('theme_purity_navdrawer', get_string('navdrawer_settings', 'theme_purity'));

    // NavDrawer Color Variation.
    $name = 'theme_purity/navdrawercolor';
    $title = get_string('navdrawercolor', 'theme_purity');
    $description = get_string('navdrawercolor_desc', 'theme_purity');
    $default = 'light';
    $options = array('light' => 'Light', 'dark' => 'Dark');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show NavDrawer Logo.
    $name = 'theme_purity/navdrawershowlogo';
    $title = get_string('navdrawershowlogo', 'theme_purity');
    $description = get_string('navdrawershowlogo_desc', 'theme_purity');
    $default = 'yes';
    $options = array('yes' => 'Yes', 'no' => 'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // NavDrawer Logo.
    $name= 'theme_purity/navdrawerlogo';
    $title = get_string('navdrawerlogo', 'theme_purity');
    $description = get_string('navdrawerlogo_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'navdrawerlogo');
    $page->add($setting);

    $settings->add($page);


    // "Footer" tab.
    $page = new admin_settingpage('theme_purity_footer', get_string('footer_settings', 'theme_purity'));

    // Footer Color Variation.
    $name = 'theme_purity/footercolor';
    $title = get_string('footercolor', 'theme_purity');
    $description = get_string('footercolor_desc', 'theme_purity');
    $default = 'light';
    $options = array('light' => 'Light', 'dark' => 'Dark');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Footer Content.
    $name = 'theme_purity/footercontent';
    $title = get_string('footercontent', 'theme_purity');
    $description = get_string('footercontent_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    // Footer Copyright Content.
    $name = 'theme_purity/footercopyrightcontent';
    $title = get_string('footercopyrightcontent', 'theme_purity');
    $description = get_string('footercopyrightcontent_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    // Footer Social Icons.
    $name = 'theme_purity/footersocialicons';
    $title = get_string('footersocialicons', 'theme_purity');
    $description = get_string('footersocialicons_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $settings->add($page);


    // "Course" tab.
    $page = new admin_settingpage('theme_purity_course', get_string('course_settings', 'theme_purity'));

    // Show Course Index.
    $name = 'theme_purity/showcourseindex';
    $title = get_string('showcourseindex', 'theme_purity');
    $description = get_string('showcourseindex_desc', 'theme_purity');
    $default = 'hide';
    $options = array('hide' => 'Hide', 'show' => 'Show');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Topics format layout.
    $name = 'theme_purity/topicsformatlayout';
    $title = get_string('topicsformatlayout', 'theme_purity');
    $description = get_string('topicsformatlayout_desc', 'theme_purity');
    $default = 'all_collapsed';
    $options = array('all_collapsed' => 'All Collapsed', 'all_but_first_collapsed' => 'All But First Collapsed', 'all_expanded' => 'All Expanded', 'default' => 'Default');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Weeks format layout.
    $name = 'theme_purity/weeksformatlayout';
    $title = get_string('weeksformatlayout', 'theme_purity');
    $description = get_string('weeksformatlayout_desc', 'theme_purity');
    $default = 'all_collapsed';
    $options = array('all_collapsed' => 'All Collapsed', 'all_but_first_collapsed' => 'All But First Collapsed', 'all_expanded' => 'All Expanded', 'default' => 'Default');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show Course Section Completion Icon.
    $name = 'theme_purity/showsectioncompletionicon';
    $title = get_string('showsectioncompletionicon', 'theme_purity');
    $description = get_string('showsectioncompletionicon_desc', 'theme_purity');
    $default = 'hide';
    $options = array('hide' => 'Hide', 'show' => 'Show');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Progress Bar.
    $name = 'theme_purity/courseprogressbar';
    $title = get_string('courseprogressbar', 'theme_purity');
    $description = get_string('courseprogressbar_desc', 'theme_purity');
    $default = 'show';
    $options = array('hide' => 'Hide', 'show' => 'Show');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Courses View.
    $name = 'theme_purity/listcourseslayout';
    $title = get_string('listcourseslayout', 'theme_purity');
    $description = get_string('listcourseslayout_desc', 'theme_purity');
    $default = 'box';
    $options = array('box' => 'Box', 'list' => 'List');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show Course Price.
    $name = 'theme_purity/showcourseprice';
    $title = get_string('showcourseprice', 'theme_purity');
    $description = get_string('showcourseprice_desc', 'theme_purity');
    $default = 'hide';
    $options = array('hide' => 'Hide', 'show-top' => 'Show Top', 'show-bottom' => 'Show Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Course Price Currency Symbol.
    $name = 'theme_purity/coursepricecurrencysymbol';
    $title = get_string('coursepricecurrencysymbol', 'theme_purity');
    $description = get_string('coursepricecurrencysymbol_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Course Price Accent Color.
    $name = 'theme_purity/coursepriceaccentcolor';
    $title = get_string('coursepriceaccentcolor', 'theme_purity');
    $description = get_string('coursepriceaccentcolor_desc', 'theme_purity');
    $default = 'primary';
    $options = array('primary' => 'Primary', 'default' => 'Secondary', 'info' => 'Info', 'success' => 'Success', 'danger' => 'Danger', 'warning' => 'Warning');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    $settings->add($page);


    // "Front Page" tab.
    $page = new admin_settingpage('theme_purity_front_page', get_string('front_page_settings', 'theme_purity'));

    // Container Width.
    $name = 'theme_purity/containerwidth';
    $title = get_string('containerwidth', 'theme_purity');
    $description = get_string('containerwidth_desc', 'theme_purity');
    $default = 'fixed';
    $options = array('fixed' => 'Fixed', 'fluid' => 'Fluid');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Page as Frontpage.
    $name = 'theme_purity/pageasfrontpage';
    $title = get_string('pageasfrontpage', 'theme_purity');
    $description = get_string('pageasfrontpage_desc', 'theme_purity');
    $default = 'yes';
    $options = array('yes' => 'Yes', 'no' => 'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show Breadcrumbs section.
    $name = 'theme_purity/showbreadcrumbs';
    $title = get_string('showbreadcrumbs', 'theme_purity');
    $description = get_string('showbreadcrumbs_desc', 'theme_purity');
    $default = 'show-page';
    $options = array('hide' => 'Hide', 'show-page' => 'Show On Pages', 'show' => 'Show');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Show Main Content section.
    $name = 'theme_purity/showmaincontent';
    $title = get_string('showmaincontent', 'theme_purity');
    $description = get_string('showmaincontent_desc', 'theme_purity');
    $default = 'hide';
    $options = array('hide' => 'Hide', 'show-page' => 'Show On Pages', 'show' => 'Show');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Fullwidth Section Title
    $name = 'theme_purity/fpfullwidth';
    $heading = get_string('fpfullwidth', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpfullwidth_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Fullwidth Background Color.
    $name = 'theme_purity/fpfullwidthbgcolor';
    $title = get_string('fpfullwidthbgcolor', 'theme_purity');
    $description = get_string('fpfullwidthbgcolor_desc', 'theme_purity');
    $default = '#f6f7f9';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Background Image.
    $name= 'theme_purity/fpfullwidthbgimage';
    $title = get_string('fpfullwidthbgimage', 'theme_purity');
    $description = get_string('fpfullwidthbgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpfullwidthbgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Background Repeat.
    $name = 'theme_purity/fpfullwidthbgrepeat';
    $title = get_string('fpfullwidthbgrepeat', 'theme_purity');
    $description = get_string('fpfullwidthbgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Background Size.
    $name = 'theme_purity/fpfullwidthbgsize';
    $title = get_string('fpfullwidthbgsize', 'theme_purity');
    $description = get_string('fpfullwidthbgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Background Position.
    $name = 'theme_purity/fpfullwidthbgposition';
    $title = get_string('fpfullwidthbgposition', 'theme_purity');
    $description = get_string('fpfullwidthbgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Background Attachment.
    $name = 'theme_purity/fpfullwidthbgattachment';
    $title = get_string('fpfullwidthbgattachment', 'theme_purity');
    $description = get_string('fpfullwidthbgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Text Color.
    $name = 'theme_purity/fpfullwidthtextcolor';
    $title = get_string('fpfullwidthtextcolor', 'theme_purity');
    $description = get_string('fpfullwidthtextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Fullwidth Heading Color.
    $name = 'theme_purity/fpfullwidthheadingcolor';
    $title = get_string('fpfullwidthheadingcolor', 'theme_purity');
    $description = get_string('fpfullwidthheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Section Title
    $name = 'theme_purity/fpintro';
    $heading = get_string('fpintro', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpintro_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Intro Background Color.
    $name = 'theme_purity/fpintrobgcolor';
    $title = get_string('fpintrobgcolor', 'theme_purity');
    $description = get_string('fpintrobgcolor_desc', 'theme_purity');
    $default = '#ffffff';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Background Image.
    $name= 'theme_purity/fpintrobgimage';
    $title = get_string('fpintrobgimage', 'theme_purity');
    $description = get_string('fpintrobgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpintrobgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Background Repeat.
    $name = 'theme_purity/fpintrobgrepeat';
    $title = get_string('fpintrobgrepeat', 'theme_purity');
    $description = get_string('fpintrobgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Background Size.
    $name = 'theme_purity/fpintrobgsize';
    $title = get_string('fpintrobgsize', 'theme_purity');
    $description = get_string('fpintrobgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Background Position.
    $name = 'theme_purity/fpintrobgposition';
    $title = get_string('fpintrobgposition', 'theme_purity');
    $description = get_string('fpintrobgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Background Attachment.
    $name = 'theme_purity/fpintrobgattachment';
    $title = get_string('fpintrobgattachment', 'theme_purity');
    $description = get_string('fpintrobgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Text Color.
    $name = 'theme_purity/fpintrotextcolor';
    $title = get_string('fpintrotextcolor', 'theme_purity');
    $description = get_string('fpintrotextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Intro Heading Color.
    $name = 'theme_purity/fpintroheadingcolor';
    $title = get_string('fpintroheadingcolor', 'theme_purity');
    $description = get_string('fpintroheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Section Title
    $name = 'theme_purity/fpfeature';
    $heading = get_string('fpfeature', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpfeature_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Feature Background Color.
    $name = 'theme_purity/fpfeaturebgcolor';
    $title = get_string('fpfeaturebgcolor', 'theme_purity');
    $description = get_string('fpfeaturebgcolor_desc', 'theme_purity');
    $default = '#f6f7f9';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Background Image.
    $name= 'theme_purity/fpfeaturebgimage';
    $title = get_string('fpfeaturebgimage', 'theme_purity');
    $description = get_string('fpfeaturebgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpfeaturebgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Background Repeat.
    $name = 'theme_purity/fpfeaturebgrepeat';
    $title = get_string('fpfeaturebgrepeat', 'theme_purity');
    $description = get_string('fpfeaturebgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Background Size.
    $name = 'theme_purity/fpfeaturebgsize';
    $title = get_string('fpfeaturebgsize', 'theme_purity');
    $description = get_string('fpfeaturebgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Background Position.
    $name = 'theme_purity/fpfeaturebgposition';
    $title = get_string('fpfeaturebgposition', 'theme_purity');
    $description = get_string('fpfeaturebgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Background Attachment.
    $name = 'theme_purity/fpfeaturebgattachment';
    $title = get_string('fpfeaturebgattachment', 'theme_purity');
    $description = get_string('fpfeaturebgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Text Color.
    $name = 'theme_purity/fpfeaturetextcolor';
    $title = get_string('fpfeaturetextcolor', 'theme_purity');
    $description = get_string('fpfeaturetextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Feature Heading Color.
    $name = 'theme_purity/fpfeatureheadingcolor';
    $title = get_string('fpfeatureheadingcolor', 'theme_purity');
    $description = get_string('fpfeatureheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Section Title
    $name = 'theme_purity/fputility';
    $heading = get_string('fputility', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fputility_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Utility Background Color.
    $name = 'theme_purity/fputilitybgcolor';
    $title = get_string('fputilitybgcolor', 'theme_purity');
    $description = get_string('fputilitybgcolor_desc', 'theme_purity');
    $default = '#ffffff';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Background Image.
    $name= 'theme_purity/fputilitybgimage';
    $title = get_string('fputilitybgimage', 'theme_purity');
    $description = get_string('fputilitybgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fputilitybgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Background Repeat.
    $name = 'theme_purity/fputilitybgrepeat';
    $title = get_string('fputilitybgrepeat', 'theme_purity');
    $description = get_string('fputilitybgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Background Size.
    $name = 'theme_purity/fputilitybgsize';
    $title = get_string('fputilitybgsize', 'theme_purity');
    $description = get_string('fputilitybgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Background Position.
    $name = 'theme_purity/fputilitybgposition';
    $title = get_string('fputilitybgposition', 'theme_purity');
    $description = get_string('fputilitybgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Background Attachment.
    $name = 'theme_purity/fputilitybgattachment';
    $title = get_string('fputilitybgattachment', 'theme_purity');
    $description = get_string('fputilitybgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Text Color.
    $name = 'theme_purity/fputilitytextcolor';
    $title = get_string('fputilitytextcolor', 'theme_purity');
    $description = get_string('fputilitytextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Utility Heading Color.
    $name = 'theme_purity/fputilityheadingcolor';
    $title = get_string('fputilityheadingcolor', 'theme_purity');
    $description = get_string('fputilityheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Section Title
    $name = 'theme_purity/fpextension';
    $heading = get_string('fpextension', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpextension_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Extension Background Color.
    $name = 'theme_purity/fpextensionbgcolor';
    $title = get_string('fpextensionbgcolor', 'theme_purity');
    $description = get_string('fpextensionbgcolor_desc', 'theme_purity');
    $default = '#f6f7f9';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Background Image.
    $name= 'theme_purity/fpextensionbgimage';
    $title = get_string('fpextensionbgimage', 'theme_purity');
    $description = get_string('fpextensionbgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpextensionbgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Background Repeat.
    $name = 'theme_purity/fpextensionbgrepeat';
    $title = get_string('fpextensionbgrepeat', 'theme_purity');
    $description = get_string('fpextensionbgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Background Size.
    $name = 'theme_purity/fpextensionbgsize';
    $title = get_string('fpextensionbgsize', 'theme_purity');
    $description = get_string('fpextensionbgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Background Position.
    $name = 'theme_purity/fpextensionbgposition';
    $title = get_string('fpextensionbgposition', 'theme_purity');
    $description = get_string('fpextensionbgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Background Attachment.
    $name = 'theme_purity/fpextensionbgattachment';
    $title = get_string('fpextensionbgattachment', 'theme_purity');
    $description = get_string('fpextensionbgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Text Color.
    $name = 'theme_purity/fpextensiontextcolor';
    $title = get_string('fpextensiontextcolor', 'theme_purity');
    $description = get_string('fpextensiontextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Extension Heading Color.
    $name = 'theme_purity/fpextensionheadingcolor';
    $title = get_string('fpextensionheadingcolor', 'theme_purity');
    $description = get_string('fpextensionheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Section Title
    $name = 'theme_purity/fpadditional';
    $heading = get_string('fpadditional', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpadditional_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Additional Background Color.
    $name = 'theme_purity/fpadditionalbgcolor';
    $title = get_string('fpadditionalbgcolor', 'theme_purity');
    $description = get_string('fpadditionalbgcolor_desc', 'theme_purity');
    $default = '#ffffff';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Background Image.
    $name= 'theme_purity/fpadditionalbgimage';
    $title = get_string('fpadditionalbgimage', 'theme_purity');
    $description = get_string('fpadditionalbgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpadditionalbgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Background Repeat.
    $name = 'theme_purity/fpadditionalbgrepeat';
    $title = get_string('fpadditionalbgrepeat', 'theme_purity');
    $description = get_string('fpadditionalbgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Background Size.
    $name = 'theme_purity/fpadditionalbgsize';
    $title = get_string('fpadditionalbgsize', 'theme_purity');
    $description = get_string('fpadditionalbgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Background Position.
    $name = 'theme_purity/fpadditionalbgposition';
    $title = get_string('fpadditionalbgposition', 'theme_purity');
    $description = get_string('fpadditionalbgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Background Attachment.
    $name = 'theme_purity/fpadditionalbgattachment';
    $title = get_string('fpadditionalbgattachment', 'theme_purity');
    $description = get_string('fpadditionalbgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Text Color.
    $name = 'theme_purity/fpadditionaltextcolor';
    $title = get_string('fpadditionaltextcolor', 'theme_purity');
    $description = get_string('fpadditionaltextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Additional Heading Color.
    $name = 'theme_purity/fpadditionalheadingcolor';
    $title = get_string('fpadditionalheadingcolor', 'theme_purity');
    $description = get_string('fpadditionalheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Section Title
    $name = 'theme_purity/fpprebottom';
    $heading = get_string('fpprebottom', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpprebottom_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Prebottom Background Color.
    $name = 'theme_purity/fpprebottombgcolor';
    $title = get_string('fpprebottombgcolor', 'theme_purity');
    $description = get_string('fpprebottombgcolor_desc', 'theme_purity');
    $default = '#f6f7f9';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Background Image.
    $name= 'theme_purity/fpprebottombgimage';
    $title = get_string('fpprebottombgimage', 'theme_purity');
    $description = get_string('fpprebottombgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpprebottombgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Background Repeat.
    $name = 'theme_purity/fpprebottombgrepeat';
    $title = get_string('fpprebottombgrepeat', 'theme_purity');
    $description = get_string('fpprebottombgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Background Size.
    $name = 'theme_purity/fpprebottombgsize';
    $title = get_string('fpprebottombgsize', 'theme_purity');
    $description = get_string('fpprebottombgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Background Position.
    $name = 'theme_purity/fpprebottombgposition';
    $title = get_string('fpprebottombgposition', 'theme_purity');
    $description = get_string('fpprebottombgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Background Attachment.
    $name = 'theme_purity/fpprebottombgattachment';
    $title = get_string('fpprebottombgattachment', 'theme_purity');
    $description = get_string('fpprebottombgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Text Color.
    $name = 'theme_purity/fpprebottomtextcolor';
    $title = get_string('fpprebottomtextcolor', 'theme_purity');
    $description = get_string('fpprebottomtextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Prebottom Heading Color.
    $name = 'theme_purity/fpprebottomheadingcolor';
    $title = get_string('fpprebottomheadingcolor', 'theme_purity');
    $description = get_string('fpprebottomheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Section Title
    $name = 'theme_purity/fpbottom';
    $heading = get_string('fpbottom', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpbottom_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Bottom Background Color.
    $name = 'theme_purity/fpbottombgcolor';
    $title = get_string('fpbottombgcolor', 'theme_purity');
    $description = get_string('fpbottombgcolor_desc', 'theme_purity');
    $default = '#ffffff';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Background Image.
    $name= 'theme_purity/fpbottombgimage';
    $title = get_string('fpbottombgimage', 'theme_purity');
    $description = get_string('fpbottombgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpbottombgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Background Repeat.
    $name = 'theme_purity/fpbottombgrepeat';
    $title = get_string('fpbottombgrepeat', 'theme_purity');
    $description = get_string('fpbottombgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Background Size.
    $name = 'theme_purity/fpbottombgsize';
    $title = get_string('fpbottombgsize', 'theme_purity');
    $description = get_string('fpbottombgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Background Position.
    $name = 'theme_purity/fpbottombgposition';
    $title = get_string('fpbottombgposition', 'theme_purity');
    $description = get_string('fpbottombgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Background Attachment.
    $name = 'theme_purity/fpbottombgattachment';
    $title = get_string('fpbottombgattachment', 'theme_purity');
    $description = get_string('fpbottombgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Text Color.
    $name = 'theme_purity/fpbottomtextcolor';
    $title = get_string('fpbottomtextcolor', 'theme_purity');
    $description = get_string('fpbottomtextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Bottom Heading Color.
    $name = 'theme_purity/fpbottomheadingcolor';
    $title = get_string('fpbottomheadingcolor', 'theme_purity');
    $description = get_string('fpbottomheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Section Title
    $name = 'theme_purity/fpafterbottom';
    $heading = get_string('fpafterbottom', 'theme_purity');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('fpafterbottom_desc', 'theme_purity'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Afterbottom Background Color.
    $name = 'theme_purity/fpafterbottombgcolor';
    $title = get_string('fpafterbottombgcolor', 'theme_purity');
    $description = get_string('fpafterbottombgcolor_desc', 'theme_purity');
    $default = '#f6f7f9';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Background Image.
    $name= 'theme_purity/fpafterbottombgimage';
    $title = get_string('fpafterbottombgimage', 'theme_purity');
    $description = get_string('fpafterbottombgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fpafterbottombgimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Background Repeat.
    $name = 'theme_purity/fpafterbottombgrepeat';
    $title = get_string('fpafterbottombgrepeat', 'theme_purity');
    $description = get_string('fpafterbottombgrepeat_desc', 'theme_purity');
    $default = 'no-repeat';
    $options = array('no-repeat' => 'No Repeat', 'repeat' => 'Repeat', 'repeat-x' => 'Repeat X', 'repeat-y' => 'Repeat Y');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Background Size.
    $name = 'theme_purity/fpafterbottombgsize';
    $title = get_string('fpafterbottombgsize', 'theme_purity');
    $description = get_string('fpafterbottombgsize_desc', 'theme_purity');
    $default = 'auto';
    $options = array('auto' => 'Auto', '100%' => '100%', 'cover' => 'Cover');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Background Position.
    $name = 'theme_purity/fpafterbottombgposition';
    $title = get_string('fpafterbottombgposition', 'theme_purity');
    $description = get_string('fpafterbottombgposition_desc', 'theme_purity');
    $default = 'left top';
    $options = array('left top' => 'Left Top', 'left center' => 'Left Center', 'left bottom' => 'Left Bottom', 'right top' => 'Right Top', 'right center' => 'Right Center', 'right bottom' => 'Right Bottom', 'center top' => 'Center Top', 'center center' => 'Center Center', 'center bottom' => 'Center Bottom');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Background Attachment.
    $name = 'theme_purity/fpafterbottombgattachment';
    $title = get_string('fpafterbottombgattachment', 'theme_purity');
    $description = get_string('fpafterbottombgattachment_desc', 'theme_purity');
    $default = 'scroll';
    $options = array('scroll' => 'Scroll', 'fixed' => 'Fixed');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Text Color.
    $name = 'theme_purity/fpafterbottomtextcolor';
    $title = get_string('fpafterbottomtextcolor', 'theme_purity');
    $description = get_string('fpafterbottomtextcolor_desc', 'theme_purity');
    $default = '#525f7f';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Afterbottom Heading Color.
    $name = 'theme_purity/fpafterbottomheadingcolor';
    $title = get_string('fpafterbottomheadingcolor', 'theme_purity');
    $description = get_string('fpafterbottomheadingcolor_desc', 'theme_purity');
    $default = '#32325d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);


    // "Login Page" tab.
    $page = new admin_settingpage('theme_purity_login_page', get_string('login_page_settings', 'theme_purity'));

    // Login Panel Position.
    $name = 'theme_purity/loginpanelposition';
    $title = get_string('loginpanelposition', 'theme_purity');
    $description = get_string('loginpanelposition_desc', 'theme_purity');
    $default = 'left';
    $options = array('left' => 'Left', 'center' => 'Center', 'right' => 'Right');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Login Page Background Image.
    $name= 'theme_purity/loginpagebgimage';
    $title = get_string('loginpagebgimage', 'theme_purity');
    $description = get_string('loginpagebgimage_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginpagebgimage');
    $page->add($setting);

    // Show Login Page Logo.
    $name = 'theme_purity/loginpageshowlogo';
    $title = get_string('loginpageshowlogo', 'theme_purity');
    $description = get_string('loginpageshowlogo_desc', 'theme_purity');
    $default = 'yes';
    $options = array('yes' => 'Yes', 'no' => 'No');
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    // Login Page Logo.
    $name= 'theme_purity/loginpagelogo';
    $title = get_string('loginpagelogo', 'theme_purity');
    $description = get_string('loginpagelogo_desc', 'theme_purity');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginpagelogo');
    $page->add($setting);

    $settings->add($page);


    // "Typography" tab.
    $page = new admin_settingpage('theme_purity_typography', get_string('typography_settings', 'theme_purity'));

    // Google Font URL.
    $name = 'theme_purity/googlefonturl';
    $title = get_string('googlefonturl', 'theme_purity');
    $description = get_string('googlefonturl_desc', 'theme_purity');
    $default = 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Body Font Family
    $name = 'theme_purity/bodyfontfamily';
    $title = get_string('bodyfontfamily', 'theme_purity');
    $description = get_string('bodyfontfamily_desc', 'theme_purity');
    $default = "'Open Sans', sans-serif";
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Headings Font Family
    $name = 'theme_purity/headingsfontfamily';
    $title = get_string('headingsfontfamily', 'theme_purity');
    $description = get_string('headingsfontfamily_desc', 'theme_purity');
    $default = "'Open Sans', sans-serif";
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Body Font Size
    $name = 'theme_purity/bodyfontsize';
    $title = get_string('bodyfontsize', 'theme_purity');
    $description = get_string('bodyfontsize_desc', 'theme_purity');
    $default = '1rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H1 Font Size
    $name = 'theme_purity/h1fontsize';
    $title = get_string('h1fontsize', 'theme_purity');
    $description = get_string('h1fontsize_desc', 'theme_purity');
    $default = '1.625rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H2 Font Size
    $name = 'theme_purity/h2fontsize';
    $title = get_string('h2fontsize', 'theme_purity');
    $description = get_string('h2fontsize_desc', 'theme_purity');
    $default = '1.25rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H3 Font Size
    $name = 'theme_purity/h3fontsize';
    $title = get_string('h3fontsize', 'theme_purity');
    $description = get_string('h3fontsize_desc', 'theme_purity');
    $default = '1.0625rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H4 Font Size
    $name = 'theme_purity/h4fontsize';
    $title = get_string('h4fontsize', 'theme_purity');
    $description = get_string('h4fontsize_desc', 'theme_purity');
    $default = '0.9375rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H5 Font Size
    $name = 'theme_purity/h5fontsize';
    $title = get_string('h5fontsize', 'theme_purity');
    $description = get_string('h5fontsize_desc', 'theme_purity');
    $default = '0.8125rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // H6 Font Size
    $name = 'theme_purity/h6fontsize';
    $title = get_string('h6fontsize', 'theme_purity');
    $description = get_string('h6fontsize_desc', 'theme_purity');
    $default = '0.625rem';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font Weight Light
    $name = 'theme_purity/fontweightlight';
    $title = get_string('fontweightlight', 'theme_purity');
    $description = get_string('fontweightlight_desc', 'theme_purity');
    $default = '300';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font Weight Normal
    $name = 'theme_purity/fontweightnormal';
    $title = get_string('fontweightnormal', 'theme_purity');
    $description = get_string('fontweightnormal_desc', 'theme_purity');
    $default = '400';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font Weight Bold
    $name = 'theme_purity/fontweightbold';
    $title = get_string('fontweightbold', 'theme_purity');
    $description = get_string('fontweightbold_desc', 'theme_purity');
    $default = '600';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font Weight Extra Bold
    $name = 'theme_purity/fontweightextrabold';
    $title = get_string('fontweightextrabold', 'theme_purity');
    $description = get_string('fontweightextrabold_desc', 'theme_purity');
    $default = '700';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);


    // "Advanced" tab.
    $page = new admin_settingpage('theme_purity_advanced', get_string('advanced_settings', 'theme_purity'));

    // Google analytics block.
    $name = 'theme_purity/googleanalytics';
    $title = get_string('googleanalytics', 'theme_purity');
    $description = get_string('googleanalytics_desc', 'theme_purity');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include before the content.
    $name = 'theme_purity/scsspre';
    $title = get_string('rawscsspre', 'theme_purity');
    $description = get_string('rawscsspre_desc', 'theme_purity');
    $setting = new admin_setting_scsscode($name, $title, $description, '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $name = 'theme_purity/scss';
    $title = get_string('rawscss', 'theme_purity');
    $description = get_string('rawscss_desc', 'theme_purity');
    $setting = new admin_setting_scsscode($name, $title, $description, '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}