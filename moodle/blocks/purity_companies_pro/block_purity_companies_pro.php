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
 * Purity Companies PRO block main class.
 */

defined('MOODLE_INTERNAL') || die();

class block_purity_companies_pro extends block_base {

    /**
     * Adds title to block instance and initializes it.
     */
    public function init() {
        $this->title = get_string('purity_companies_pro', 'block_purity_companies_pro');
    }

    /**
     * Overrides block instance content.
     * Called immediately after init().
     */
	public function specialization() {

		// Set defaults
		if (empty($this->config)) {
			$this->config = new \stdClass();
			$this->config->show_as_card = '0';
			$this->config->show_header = '1';
			$this->config->custom_title = 'Our Awesome Clients';
			$this->config->custom_subtitle = 'Sed hendrerit quam sed ante euismod posu. Mauris ut elementum ante. Vestibuel suscipit convallis purus mattis magna sapien, euismod convallis sagittis quis euismod posu.';
			$this->config->behaviour = 'slider';
			$this->config->items_per_row = '3';
			$this->config->style = '0';
			$this->config->companies = '3';
		}

	    if (isset($this->config)) {
	        if (empty($this->config->custom_title)) {
	            $this->title = get_string('purity_companies_pro', 'block_purity_companies_pro');            
	        } else {
	            $this->title = format_text($this->config->custom_title, FORMAT_HTML, array('filter' => true));
	        }
	    }
	}

    /**
     * Gets block instance content.
     */
	public function get_content() {
		global $CFG;

	    if ($this->content !== null) {
	      return $this->content;
	    }

	    // Set items
        if (!empty($this->config) && is_object($this->config)) {
            $data = $this->config;
            $data->companies = is_numeric($data->companies) ? (int)$data->companies : 3;
        } else {
            $data = new stdClass();
            $data->companies = 0;
        }

		// Set defaults
		if (!isset($this->config->autoplay)) { $this->config->autoplay = 'true'; }
		if (!isset($this->config->autoplay_interval)) { $this->config->autoplay_interval = '6000'; }
		if (!isset($this->config->pause_hover)) { $this->config->pause_hover = 'true'; }
	 
	    $this->content = new stdClass;

	    // Block Intro
	    if ($this->config->show_header && $this->config->show_as_card == '0') {
	    	$block_intro = '
	    		<div class="purity-block-intro">
	    			<h3 class="block-main-title">' . $this->title . '</h3>';

	    	if ($this->config->custom_subtitle) {
	    		$block_intro .= '
	    			<div class="block-title-separator"></div>
	    			<div class="block-subtitle">' . format_text($this->config->custom_subtitle, FORMAT_HTML, array('filter' => true)) . '</div>';
	    	}

	    	$block_intro .= '</div>';
	    } else {
	    	$block_intro = '';
	    }

	    // Items
	    $items = '';
	    if ($data->companies > 0) {
	    	$fs = get_file_storage();
	    	for ($i = 1; $i <= $data->companies; $i++) {
				$company_logo = 'company_logo' . $i;
				$company_url = 'company_url' . $i;
				$company_url_target = 'company_url_target' . $i;
				$company_css_class = 'company_css_class' . $i;

				// Image URL
				$files = $fs->get_area_files($this->context->id, 'block_purity_companies_pro', 'companies', $i, 'sortorder DESC, id ASC', false, 0, 0, 1);
				if (!empty($data->$company_logo) && count($files) >= 1) {
					$mainfile = reset($files);
					$mainfile = $mainfile->get_filename();
					$data->$company_logo = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php", "/{$this->context->id}/block_purity_companies_pro/companies/" . $i . '/' . $mainfile);
				} else {
					$data->$company_logo = $CFG->wwwroot .'/theme/purity/pix/company1.png';
				}
				
				// Image
				if ($data->$company_logo) {
					if ($data->$company_url) {
						$image = '
							<a href="' . $data->$company_url . '" target="' . $data->$company_url_target . '">
								<img src="' . $data->$company_logo . '">
							</a>';
					} else {
						$image = '<img src="' . $data->$company_logo . '">';
					}
				} else {
					$image = '';
				}

				// Item
				if ($this->config->style == '0') {
					$style_item = '
						<div class="card ' . $data->$company_css_class . '">
							<div class="card-body">
								' . $image . '
							</div>
						</div>';
				} else if ($this->config->style == '1') {
					$style_item = '
						<div class="company-style2-wrapper ' . $data->$company_css_class . '">
							' . $image . '
						</div>';
				}

				// Company Items
				if ($this->config->behaviour == 'slider') {
					$items .= '<li>' . $style_item . '</li>';
				} else {
					$items .= '<div class="uk-width-1-' . $this->config->items_per_row . '@m">' . $style_item . '</div>';
				}

	    	}
	    }

	    // Slider Options
	    $slider_options = '
	    	autoplay: ' . $this->config->autoplay . ';
	    	autoplay-interval: ' . $this->config->autoplay_interval . ';
	    	pause-on-hover: ' . $this->config->pause_hover . ';
	    ';
	    $navigation = $this->config->navigation == 'arrows' || 'both' ? 'uk-visible-toggle-custom' : '';

	    // Navigation Arrows
	    if ($this->config->navigation == 'none' || $this->config->navigation == 'dots') {
	    	$arrows = '';
	    } else {
	    	$arrows = '
	    		<a class="uk-position-center-left uk-position-medium uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
			    <a class="uk-position-center-right uk-position-medium uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
	    	';
	    }

	    // Navigation Dots
	    if ($this->config->navigation == 'none' || $this->config->navigation == 'arrows') {
	    	$dots = '';
	    } else {
	    	$dots = '<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>';
	    }

	    // Final Items
	    if ($this->config->behaviour == 'slider') {
	    	$final_items = '
	    		<div class="uk-slider-container-offset" uk-slider="' . $slider_options . '">
	    			<div class="uk-position-relative ' . $navigation . '" tabindex="-1">
			    		<ul class="uk-slider-items uk-child-width-1-2@s uk-child-width-1-' . $this->config->items_per_row . '@m uk-grid uk-grid-medium">
			    			' . $items . '
			    		</ul>

			    		' . $arrows . '
			    	</div>

			    	' . $dots . '
	    		</div>';
	    } else {
	    	$final_items = '
	    		<div class="uk-grid-medium" uk-grid>
	    			' . $items . '
	    		</div>';
	    }


	    $this->content->text = '
	    	<div class="companies-pro-container">
	    		' . $block_intro . '
	    		' . $final_items . '
	    	</div>';

	    // $this->content->footer = '';
	 
	    return $this->content;
	}

    /**
     * Store block instance data.
     */
    function instance_config_save($data, $nolongerused = false) {
        global $CFG;

        for($i = 1; $i <= $data->companies; $i++) {
            $field = 'company_logo' . $i;
            if (!isset($data->$field)) {
                continue;
            }

            file_save_draft_area_files($data->$field, $this->context->id, 'block_purity_companies_pro', 'companies', $i,
            	array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 1,
                'accepted_types' => array('.png', '.jpg', '.gif', '.svg') ));
        }

        parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Delete block instance data.
     */
    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_purity_companies_pro');
        return true;
    }

    /**
     * Modifies the HTML attributes of the block.
     */
	public function html_attributes() {
	    $attributes = parent::html_attributes(); // Get default values

		if ($this->config->show_header == '0') {
			$attributes['class'] .= ' block_hide_header';
		}

		if ($this->config->show_as_card == '0') {
			$attributes['class'] .= ' block_not_card';
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
		return array('all' => true);
	}

}
