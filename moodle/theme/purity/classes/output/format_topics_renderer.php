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

class theme_purity_format_topics_renderer extends \format_topics\output\renderer {

  // Provide theme settings to course mustache templates
  protected function render_content(\renderable $widget) {
    $theme = theme_config::load('purity');

    $data = $widget->export_for_template($this);
    
    if ($data->sections) {
      $data->sections[0]->is_first_section = true;
    }

    if ($theme->settings->topicsformatlayout == 'all_but_first_collapsed') {
      $data->all_but_first_collapsed = true;
    } else if ($theme->settings->topicsformatlayout == 'all_expanded') {
      $data->all_expanded = true;
    } else if ($theme->settings->topicsformatlayout == 'default') {
      $data->default_layout = true;
    } else {
      $data->all_collapsed = true;
    }

    return $this->render_from_template($widget->get_template_name($this), $data);
  }

}