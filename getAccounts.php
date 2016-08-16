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

if (!ispost()) {
    die;
}

$apikey = optional_param('apikey', '', PARAM_TEXT);

if ($curl = curl_init()) {
    curl_setopt($curl, CURLOPT_URL, $dataconnect->getpath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('apikey' => $apikey));
    $out = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($out, true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {
            renderjson($json['response']['accounts'], '');
        } else if (isset($json['error'])) {
            renderjson ('', implode('<br>', $json['error']));
        } else if (isset($json['validate'])) {
            renderjson('', 'Error validate: '.implode(', ', $json['validate']));
        }
    } else {
        $error = (isset($json['message'])) ? $json['message'] : 'error';
    }
} else {
    $error = get_string('curlerror', 'etutorium');
}
