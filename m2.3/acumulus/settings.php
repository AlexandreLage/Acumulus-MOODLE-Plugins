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
 * This file adds the settings pages to the navigation menu
 *
 * @package   mod_acumulus
 * @copyright 2016 Acumulus {@link http://www.com.br}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die;

//Set text fields to the setting page.
//It just needs to "add" fields to $settings

//Creates a new text field (admin_setting_configtext). It could be checkbox, radio, etc. 
//See https://docs.moodle.org/dev/Admin_settings
$settings->add(new admin_setting_configtext('i_name', get_string('i_name', 'acumulus'),
                    get_string('i_name_desc', 'acumulus'), null));

$settings->add(new admin_setting_configtext('i_initials', get_string('i_initials', 'acumulus'),
                    get_string('i_initials_desc', 'acumulus'), null));
                    
$settings->add(new admin_setting_configtext('i_key', get_string('i_key', 'acumulus'),
                    get_string('i_key_desc', 'acumulus'), null));

$settings->add(new admin_setting_configtext('i_moodle_url', get_string('i_moodle_url', 'acumulus'),
                    get_string('i_moodle_url_desc', 'acumulus'), null));
                    
$settings->add(new admin_setting_configtext('i_acumulus_url', get_string('i_acumulus_url', 'acumulus'),
                    get_string('i_acumulus_url_desc', 'acumulus'), null));
                    
//It does not neeed to create a table to store the settings fields
//All data will be stored in "mdl_config" table on moodle's db

