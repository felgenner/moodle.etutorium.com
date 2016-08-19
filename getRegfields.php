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
$apikey = required_param('apikey', PARAM_TEXT);

$result = '';
$error = '';

if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $dataconnect->getpath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'webinar_id' => $webinarid,
        'apikey' => $apikey,
        'email' => $USER->email,
    ));
    $out = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($out, true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {
            $fields = array();
            $requiredfield = array();
            foreach ($json['response']['regfields'] as $field) {
                $fields[] = 'field'.$field['id'];
                if ($field['required']) {
                    $requiredfield[] = 'field'.$field['id'];
                }
            }
            if (!empty($json['response']['regfields'])) {
                $result = etutorium_renderfile('getRegfields', array(
                    'data' => array(
                        'data' => $json['response'],
                        'apikey' => $apikey,
                        'countrequired' => count($requiredfield),
                        'webinarid' => $webinarid
                        )
                    ));
            } else {
                $result = 'ok';
            }
            etutorium_renderjson(array(
                'table' => $result,
                'fieldlist' => $fields,
                'requiredfieldlist' => $requiredfield,
                'count' => count($json['response']['regfields']),
            ), $error);
        } else if (isset($json['error'])) {
            etutorium_renderjson ('', implode('<br>', $json['error']));
        } else if (isset($json['validate'])) {
            etutorium_renderjson('', 'Error validate: '.implode(', ', $json['validate']));
        }
    } else {
        $error = (isset($json['message'])) ? $json['message'] : 'error';
    }
} else {
    $error = get_string('curlerror', 'etutorium');
}

etutorium_renderjson($result, $error);
