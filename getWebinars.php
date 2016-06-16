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
 * Get all webinars with some parameters
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @package mod_etutorium
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$id = required_param('etutorium', PARAM_INT);
$apikey = required_param('apikey', PARAM_TEXT);
$accountid = optional_param('account_id', 0, PARAM_INT);
$starttime = optional_param('start_time', '', PARAM_TEXT);
$finishtime = optional_param('finish_time', '', PARAM_TEXT);

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

$result = '';
$error = '';
$request = array(
    'apikey' => $apikey,
    'etutorium' => $id,
);
if ($accountid != 0) {
    $request['account_id'] = $accountid;
}
if ($starttime != '') {
    $request['start_time'] = $starttime;
}
if ($finishtime != '') {
    $request['finish_time'] = $finishtime;
}

if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $dataconnect->getpath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    $out = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($out, true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {

            foreach ($json['response'] as $key => $value) {
                $json['response'][$key]['start_time'] = changetimewithtimezone($value['start_time'], $value['timezone']);
                if (!empty($value['finish_time'])) {
                    $json['response'][$key]['finish_time'] = changetimewithtimezone($value['finish_time'], $value['timezone']);
                }
            }

            $exist = $DB->get_records_sql('select webinar_id from {etutoriumwebinars} where etutorium_id=?', array($id));
            $date = date('Y-m-d');
            foreach ($json['response'] as $key => $value) {
                if ($value['start_time'] < $date) {
                    $json['response'][$key]['status'] = 'past';
                } else {
                    $json['response'][$key]['status'] = 'future';
                }
                foreach ($exist as $existvalue) {
                    if ($existvalue->webinar_id == $value['id']) {
                        unset ($json['response'][$key]);
                    }
                }
            }
            $result = renderfile('getWebinars', array(
                'data' => array(
                    'data' => $json['response'],
                    'id' => 'allweblist',
                    'action' => 'add',
                    'apikey' => $apikey,
                    'etutorium_id' => $id)
                ));
            renderjson([
                'table' => $result,
                'id' => 'allweblist',
                'data' => $json['response'],
            ], $error);
        } else if (isset($json['error'])) {
            renderjson ('', implode('<br>', $json['error']));
        } else if (isset($json['validate'])) {
            renderjson('', 'Error validate: '.  implode(', ', $json['validate']));
        }
    } else {
        $error = (isset($json['message'])) ? $json['message'] : 'error';
    }
} else {
    $error = get_string('curlerror', 'etutorium');
}

renderjson($result, $error);

/**
 * spike. change time with use other timezone
 * @param string $time
 * @param string $timezone
 * @return string
 */
function changetimewithtimezone($time, $timezone) {
    $newtime = new DateTime($time, new DateTimeZone($timezone));
    $matches = null;
    if (preg_match('/(\+|\-)([0-9]{2})\:([0-9]{2})/', $newtime->format('P'), $matches)) {
        $interval = new DateInterval('PT'.(int)$matches[2].'H'.(int)$matches[3].'M');
        return $newtime->add($interval)->format('Y-m-d H:i:s');
    } else {
        return $time;
    }
}