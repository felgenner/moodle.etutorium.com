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
defined('MOODLE_INTERNAL') || die();

$webinarid = required_param('id', PARAM_INT);
$etutoriumid = required_param('etutorium_id', PARAM_INT);
$title = required_param('title', PARAM_TEXT);
$description = required_param('description', PARAM_TEXT);
$starttime = optional_param('start_time', 0, PARAM_TEXT);
$finishtime = optional_param('finish_time', 0, PARAM_TEXT);

if (! $etutorium = $DB->get_record('etutorium', array('id' => $etutoriumid))) {
    error('Course module is incorrect');
}
if (! $course = $DB->get_record('course', array('id' => $etutorium->course))) {
    error('Course is misconfigured');
}
if (! $cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
    error('Course Module ID was incorrect');
}
require_login($course, true, $cm);

$context = context_course::instance($course->id);
if (!has_capability('mod/etutorium:addwebinar', $context)) {
    etutorium_renderjson('', get_string('permission-denied', 'etutorium'));
}

$record = $DB->get_records_sql('select id from {etutoriumwebinars} where webinar_id = ? and etutorium_id = ?',
    array($webinarid, $etutoriumid));
if (!empty($record)) {
    etutorium_renderjson ('', get_string('webinar_exist', 'etutorium'));
} else {
    $newwebinar = new stdClass();
    $newwebinar->etutorium_id = $etutoriumid;
    $newwebinar->webinar_id = $webinarid;
    $newwebinar->title = $title;
    $newwebinar->description = $description;

    if (!empty($starttime)) {
        $newtime = new DateTime($starttime);
        $newstarttime = $newtime->getTimestamp();
    } else {
        $newstarttime = 0;
    }

    $newwebinar->start_time = $newstarttime;

    if (empty($finishtime) || $finishtime == get_string('finish_time_undefined', 'etutorium')) {
        $newfinishtime = 0;
    } else {
        $newtime = new DateTime($finishtime);
        $newfinishtime = $newtime->getTimestamp();
    }

    $newwebinar->finish_time = $newfinishtime;

    $DB->insert_record('etutoriumwebinars', $newwebinar);
    etutorium_renderjson('ok');
}