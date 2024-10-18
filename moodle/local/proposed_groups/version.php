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

$plugin->component = 'local_proposed_groups';    // Full name of the plugin (used for diagnostics).
$plugin->version   = 2023101500;                 // The current plugin version (YYYYMMDDXX).
$plugin->requires  = 2022041900;                 // Requires this Moodle version (4.0 or higher).
$plugin->maturity  = MATURITY_STABLE;            // This is a stable version.
$plugin->release   = '1.0.0';                    // This is the first release.
$plugin->cron      = 0;                          // Period for cron to check this plugin (in seconds).
