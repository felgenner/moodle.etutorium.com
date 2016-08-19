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
 * local library with functions render and view him or view json
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @package mod_etutorium
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * read file and return
 * @param string $view filename
 * @param array $etutoriummynewdata data, using in file
 * @param string $path path to file or use default path
 * @return string
 */
function etutorium_renderfile($view, $etutoriummynewdata = array(), $path = '') {
    global $CFG;
    if (empty($path)) {
        $path = 'mod/'.basename(__DIR__);
    }
    $filepath = $CFG->dirroot.'/'.$path.'/'.$view.'View.php';
    $content = '';
    if (file_exists($filepath)) {
        foreach ($etutoriummynewdata as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include($filepath);
        $content = ob_get_contents();
        ob_end_clean();
    }
    return $content;
}

/**
 * view data in json format
 * @param string $result result
 * @param string $error error
 */
function etutorium_renderjson($result, $error = '') {
    $resultarr = array(
        'error' => $error,
        'result' => $result,
    );
    echo json_encode($resultarr);
    exit;
}

/**
 * return true or false if use post request
 * @return bool
 */
function etutorium_ispost() {
    $headers = apache_request_headers();
    return count($_POST) > 0 || isset($headers['Content-Type']);
}
