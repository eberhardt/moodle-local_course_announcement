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
include_once "toolbox.php";

function local_course_announcement_notification () {
    global $COURSE, $SITE, $PAGE;

    $site = isset($SITE) ? $SITE : get_site();
	$path = explode("/", $PAGE->category->path);
	$coursecat = $path[1];
        $coursecats = get_categories_list();

    if (isset($COURSE) && $COURSE->id != $site->id) {
        $config = get_config("local_course_announcement");


	if ($config->categories) {
	    //category announcements enabled, display each category instead of global course announcement
				$name = 'coursecatmessage'.$coursecat;
				$visname = 'setting_visible'.$coursecat;
				$catmessage = $config->$name;
				$catvisible = $config->$visname;


			    if ($catmessage != "" && $catvisible==true) {
				$options = array("context" => context_course::instance($site->id), "trusted" => true, "para" => false);
	                        $message = format_text($catmessage, FORMAT_MOODLE, $options);
        	                \core\notification::add($message, \core\output\notification::NOTIFY_INFO);
                	        echo \html_writer::script("(function() {" .
                                             "var notificationHolder = document.getElementById('user-notifications');" .
                                              "if (!notificationHolder) { return; }" .
                                              "notificationHolder.className += ' courseannouncement'" .
                                               "})();"
                                                );

			    }


	} else {
			if ($config->visible) {
        	    	//use context_course rather then context_system because of caching
           	 	$options = array("context" => context_course::instance($site->id), "trusted" => true, "para" => false);
           	 	$message = format_text($config->message, FORMAT_MOODLE, $options);
           	 	\core\notification::add($message, \core\output\notification::NOTIFY_INFO);
            	 	echo \html_writer::script("(function() {" .
                 	                     "var notificationHolder = document.getElementById('user-notifications');" .
                        	              "if (!notificationHolder) { return; }" .
                                	      "notificationHolder.className += ' courseannouncement'" .
                               		       "})();"
            					);
        		}
   	}
    }
	return true;
}

