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
 * Add webinar to database
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @package mod_etutorium
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$webinarid = optional_param('id', '', PARAM_INT);
$etutoriumid = optional_param('etutorium_id', '', PARAM_INT);
$title = optional_param('title', '', PARAM_TEXT);
$description = optional_param('description', '', PARAM_TEXT);
$starttime = optional_param('start_time', '', PARAM_TEXT);
$finishtime = optional_param('finish_time', '', PARAM_TEXT);

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
    renderjson('', get_string('permission-denied', 'etutorium'));
}

$record = $DB->get_records_sql('select id from {etutoriumwebinars} where webinar_id = ? and etutorium_id = ?',
    array($webinarid, $etutoriumid));
if (!empty($record)) {
    renderjson ('', get_string('webinar_exist', 'etutorium'));
} else {
    $newwebinar = new stdClass();
    $newwebinar->etutorium_id = $etutoriumid;
    $newwebinar->webinar_id = $webinarid;
    $newwebinar->title = $title;
    $newwebinar->description = $description;
    $newwebinar->start_time = (!empty($starttime) && (strtolower($starttime)) != 'null') ? $starttime : '0000-00-00 00:00:00';
    $newwebinar->finish_time = (!empty($finishtime) && (strtolower($finishtime)) != 'null') ? $finishtime : '0000-00-00 00:00:00';
    $DB->insert_record('etutoriumwebinars', $newwebinar);
    renderjson('ok');
}
