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
require_once(dirname(__FILE__).'/locallib.php');

if (!etutorium_ispost()) {
    die;
}

$id = required_param('id', '', PARAM_INT);
$etutoriumid = required_param('etutorium_id', '', PARAM_INT);

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

$DB->delete_records('etutoriumwebinars', array('id' => $id, 'etutorium_id' => $etutoriumid));
etutorium_renderjson('ok');
