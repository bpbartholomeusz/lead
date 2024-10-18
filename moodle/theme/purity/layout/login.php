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

defined('MOODLE_INTERNAL') || die();

$bodyattributes = $OUTPUT->body_attributes();

// Login Panel Position Class
$login_panel_position_class = get_config('theme_purity', 'loginpanelposition');

// Login Page Background Image
if ($OUTPUT->get_filearea_image_url('loginpagebgimage')) {
  $login_page_bg_image_url = $OUTPUT->get_filearea_image_url('loginpagebgimage');
}

// Show Language Menu
$haslangmenu = $this->lang_menu() != '';
if ($haslangmenu) {
  $show_lang_menu = true;
}  else {
  $show_lang_menu = false;
}

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'login_panel_position_class' => $login_panel_position_class,
    'login_page_bg_image_url' => $login_page_bg_image_url,
    'show_lang_menu' => $show_lang_menu
];

$PAGE->requires->jquery();

echo $OUTPUT->render_from_template('theme_purity/login', $templatecontext);

