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

$id = optional_param('id', 0, PARAM_INT);
$a  = optional_param('a', 0, PARAM_INT);

if ($id) {
    if (! $cm = get_coursemodule_from_id('etutorium', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
        error('Course is misconfigured');
    }

    if (! $etutorium = $DB->get_record('etutorium', array('id' => $cm->instance))) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $etutorium = $DB->get_record('etutorium', array('id' => $a))) {
        error('Course module is incorrect');
    }
    if (! $course = $DB->get_record('course', array('id' => $etutorium->course))) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, 'etutorium', 'view', 'view.php?id='.$cm->id, $etutorium->id);

$stretutoriums = get_string('modulenameplural', 'etutorium');
$stretutorium  = get_string('modulename', 'etutorium');

$PAGE->set_url('/mod/etutorium/view.php', array('id' => $cm->id));

$context = context_course::instance($course->id);

echo $OUTPUT->header();

$usewebinar = $DB->get_records('etutoriumwebinars', array('etutorium_id' => $etutorium->id));
$PAGE->requires->js('/mod/etutorium/js/send.js');

if (has_capability('mod/etutorium:addwebinar', $context)) {
    $PAGE->requires->js('/mod/etutorium/js/edit.js');
    $PAGE->requires->strings_for_js(array(
        'title',
        'description',
        'start_time',
        'finish_time',
        'adderror',
    ), 'etutorium');
    echo renderfile('admin', array(
        'etutorium' => $etutorium,
        'userweblist' => $usewebinar,
        'id' => 'useweblist',
    ));
} else if (has_capability('mod/etutorium:connect', $context)) {
    $PAGE->requires->js('/mod/etutorium/js/connect.js');
    $PAGE->requires->strings_for_js(array(
        'fullingfields',
        'participant-webinar-not-found',
    ), 'etutorium');
    echo renderfile('student', array(
        'etutorium' => $etutorium,
        'userweblist' => $usewebinar,
    ));
}

echo $OUTPUT->footer();
