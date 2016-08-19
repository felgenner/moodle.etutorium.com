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
require_once(dirname(__FILE__).'/dataconnect.php');

$webinarid = required_param('webinar_id', PARAM_INT);
$id = required_param('id', PARAM_INT);

$etutorium = $DB->get_record('etutorium', array('id' => $id));
$webinar = $DB->get_record('etutoriumwebinars', array('webinar_id' => $webinarid, 'etutorium_id' => $etutorium->id));

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

if (empty($etutorium) || empty($webinar)) {
    $error = get_string('webinarnotfound', 'etutorium');
}

function webinarconnect($webinar, $etutorium, $dataconnect) {
    global $USER;
    $result = '';
    $error = '';
    if ($curl = curl_init()) {
        $request = array(
            'apikey' => $etutorium->apikey,
            'webinar_id' => $webinar->webinar_id,
            'email' => $dataconnect->getemail(),
            'first_name' => $dataconnect->getfirstname(),
            'last_name' => $dataconnect->getlastname(),
        );

        curl_setopt($curl, CURLOPT_URL, $dataconnect->getpath('webinarConnect'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        $out = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($out, true);

        if (isset ($json['ok'])) {
            if ($json['ok']) {
                if (headers_sent()) {
                    $result = '<script> document.location.href = \''.$json['response']['authurl'].'\'; </script>';
                } else {
                    header( 'Location: '.$json['response']['authurl'] );
                }
            } else if (isset($json['error'])) {
                $error = implode('<br>', $json['error']);
            } else if (isset($json['validate'])) {
                $error = 'Error validate: '.  implode(', ', $json['validate']);
            }
        } else {
            $error = (isset($json['message'])) ? $json['message'] : 'error';
        }
    } else {
        $error = get_string('curlerror', 'etutorium');
    }

    return array($result, $error);
}

list($result, $error) = webinarconnect($webinar, $etutorium, $dataconnect);

if (!$etutorium = $DB->get_record('etutorium', array('id' => $id))) {
    error('Course module is incorrect');
}
if (!$course = $DB->get_record('course', array('id' => $etutorium->course))) {
    error('Course is misconfigured');
}
if (!$cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
    error('Course Module ID was incorrect');
}

require_login($course, true, $cm);
$PAGE->set_url('/mod/etutorium/view.php', array('webinar_id' => $webinarid, 'id' => $id));
$PAGE->requires->js('/mod/etutorium/js/send.js');
$PAGE->requires->js('/mod/etutorium/js/connect.js');
$PAGE->requires->strings_for_js(array(
    'fullingfields',
    'participant-webinar-not-found',
), 'etutorium');

echo $OUTPUT->header();
echo etutorium_renderfile('connect', array('result' => $result, 'error' => $error, 'etutorium' => $etutorium));
echo $OUTPUT->footer();