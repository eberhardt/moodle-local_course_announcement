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
 * Version information
 *
 * @package    local_course_announcement
 * @copyright  2016 Jan Eberhardt <eberhardt@math.tu-berlin.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->locate("localplugins")) {
    $tmp = new admin_settingpage("course_announcement", get_string("pluginname", "local_course_announcement"));

    //Toggle for category level course announcements
    $tmp->add(new admin_setting_configcheckbox("local_course_announcement/categories",
                                               get_string("setting_category", "local_course_announcement"),
                                               get_string("setting_category_info", "local_course_announcement"),
                                               false));

   //check category setting and build page appropriately
   if (get_config("local_course_announcement", "categories")) {

	// Get all category IDs and their names.  Borrowed with thanks from theme_essential.
            $coursecats = \course_announcement\toolbox::get_categories_list();

    // Iterate through catagories and create settings.
      foreach ($coursecats as $key => $value) {
	if ($value->depth == 1) {
		$namepath = join(' / ', $value->namechunks);
		$announcestring = get_string("setting_message", "local_course_announcement");
		$announcedesc = get_string("setting_catmessage_info", "local_course_announcement");
		$catvisible = get_string("setting_visible", "local_course_announcement");

		//Site Wide Announcement
		$tmp->add(new admin_setting_confightmleditor("local_course_announcement/message",
                                                 get_string("setting_message", "local_course_announcement"),
                                                 get_string("setting_message_info", "local_course_announcement"),
                                                 ""));
       		$tmp->add(new admin_setting_configcheckbox("local_course_announcement/visible",
                                               get_string("setting_visible", "local_course_announcement"),
                                               get_string("setting_visible_info", "local_course_announcement"),
                                               false));


		//Build category announcement settings
		$name = "local_course_announcement/coursecatmessage";
		$title = $namepath." ".$announcestring;
		$description = get_string("setting_catmessage_info", "local_course_announcement", array("category" => $namepath));
		$default = '';
        	$setting = new admin_setting_confightmleditor($name.$key, $title, $description, $default);
		$tmp->add($setting);

		//Build catogory announcement toggle settings
		$name = "local_course_announcement/setting_visible";
                $title = $namepath." ".$catvisible;
                $description = get_string("setting_visible_info", "local_course_announcement", array("category" => $namepath));
		$default = false;
                $setting = new admin_setting_configcheckbox($name.$key, $title, $description, $default);
                $tmp->add($setting);
	}
     }

   $ADMIN->add("localplugins", $tmp);

   } else {
       $tmp->add(new admin_setting_confightmleditor("local_course_announcement/message",
                                                 get_string("setting_message", "local_course_announcement"),
                                                 get_string("setting_message_info", "local_course_announcement"),
                                                 ""));
       $tmp->add(new admin_setting_configcheckbox("local_course_announcement/visible",
                                               get_string("setting_visible", "local_course_announcement"),
                                               get_string("setting_visible_info", "local_course_announcement"),
                                               false));

       $ADMIN->add("localplugins", $tmp);
   }
}
