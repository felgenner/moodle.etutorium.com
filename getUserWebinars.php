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

$id = required_param('etutorium', PARAM_INT);
$apikey = required_param('apikey', PARAM_TEXT);

$usewebinar = $DB->get_records('etutoriumwebinars', array('etutorium_id' => $id));
$u = array();
foreach ($usewebinar as $value) {
    $value->start_time = date('Y-m-d H:i:s', $value->start_time);
    if (empty($value->finish_time)) {
        $value->finish_time = get_string('finish_time_undefined', 'etutorium');
    } else {
        $value->finish_time = date('Y-m-d H:i:s', $value->finish_time);
    }
    $u[] = $value;
}
etutorium_renderjson([
    'table' => etutorium_renderfile('getWebinars', array(
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