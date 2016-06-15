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
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @version 1.0
 * @package mod/etutorium
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);

if (! $course = get_record('course', 'id', $id)) {
    error('Course ID is incorrect');
}

require_course_login($course);

add_to_log($course->id, 'etutorium', 'view all', "index.php?id=$course->id", '');

$stretutoriums = get_string('modulenameplural', 'etutorium');
$stretutorium  = get_string('modulename', 'etutorium');

$navlinks = array();
$navlinks[] = array('name' => $stretutoriums, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header_simple($stretutoriums, '', $navigation, '', '', true, '', navmenu($course));

if (! $etutoriums = get_all_instances_in_course('etutorium', $course)) {
    notice('There are no instances of etutorium', "../../course/view.php?id=$course->id");
    die;
}

$timenow  = time();
$strname  = get_string('name');
$strweek  = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks') {
    $table->head  = array ($strweek, $strname);
    $table->align = array ('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array ($strtopic, $strname);
    $table->align = array ('center', 'left', 'left', 'left');
} else {
    $table->head  = array ($strname);
    $table->align = array ('left', 'left', 'left');
}

foreach ($etutoriums as $etutorium) {
    if (!$etutorium->visible) {
        $link = '<a class="dimmed" href="view.php?id='.$etutorium->coursemodule.'">'.format_string($etutorium->name).'</a>';
    } else {
        $link = '<a href="view.php?id='.$etutorium->coursemodule.'">'.format_string($etutorium->name).'</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array($etutorium->section, $link);
    } else {
        $table->data[] = array($link);
    }
}

print_heading($stretutoriums);
print_table($table);

print_footer($course);
