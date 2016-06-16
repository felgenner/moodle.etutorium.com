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
 * Get using webinars from database
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @package mod_etutorium
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('etutorium', '', PARAM_INT);
$apikey = optional_param('apikey', '', PARAM_TEXT);

if (! $etutorium = $DB->get_record('etutorium', array('id' => $id))) {
    error('Course module is incorrect');
}
if (! $course = $DB->get_record('course', array('id' => $etutorium->course))) {
    error('Course is misconfigured');
}
if (! $cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
    error('Course Module ID was incorrect');
}
require_login($course, true, $cm);

$usewebinar = $DB->get_records('etutoriumwebinars', array('etutorium_id' => $id));
$u = array();
foreach ($usewebinar as $value) {
    $u[] = $value;
}
renderjson([
    'table' => renderfile('getWebinars', array(
        'data' => array(
            'data' => $u,
            'id' => 'userweblist',
            'action' => 'del',
            'apikey' => $apikey,
            'etutorium_id' => $id)
        )),
    'id' => 'userweblist',
    'data' => $u,
], '');