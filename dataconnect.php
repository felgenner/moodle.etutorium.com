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

class dataconnect
{
    const PROTOCOL = 'https';
    const DOMAIN = 'api.etutorium.com';

    public $firstname = '';
    public $lastname = '';
    public $email = '';

    public function __construct() {
        global $USER;
        $this->firstname = (!empty($USER->firstname)) ? $USER->firstname : '';
        $this->lastname = (!empty($USER->lastname)) ? $USER->lastname : '';
        $this->email = $USER->email;
    }

    public function getfirstname() {
        return $this->firstname;
    }

    public function getlastname() {
        return $this->lastname;
    }

    public function getemail() {
        return $this->email;
    }

    public function returnpath($pathname) {
        $path = array(
            'getAccounts' => '/api/moodle/account-list',
            'getWebinars' => '/api/moodle/webinar-list',
            'getRegfields' => '/api/moodle/regfields',
            'webinarConnect' => '/api/moodle/participant',
        );
        return $path[$pathname];
    }

    public function getpath($pathname) {
        return self::PROTOCOL.'://'.self::DOMAIN.$this->returnpath(str_replace('.php', '', $pathname));
    }
}
$dataconnect = new dataconnect();