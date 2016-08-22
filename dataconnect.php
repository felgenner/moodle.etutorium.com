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
 * Some class, keep etutorium connect data and some user data
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @package mod_etutorium
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/locallib.php');
/**
 * Some class, keep etutorium connect data and some user data
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class dataconnect
{
    /**
     * protocol use etutorium
     */
    const PROTOCOL = 'https';
    /**
     * etutorium domain
     */
    const DOMAIN = 'api.etutorium.com';

    /**
     * keep user first name
     * @var string
     */
    public $firstname = '';
    /**
     * keep user last name
     * @var string
     */
    public $lastname = '';
    /**
     * keep user email
     * @var string
     */
    public $email = '';

    /**
     * class constructor
     */
    public function __construct() {
        global $USER;
        $this->firstname = (!empty($USER->firstname)) ? $USER->firstname : '';
        $this->lastname = (!empty($USER->lastname)) ? $USER->lastname : '';
        $this->email = $USER->email;
    }

    /**
     * return user first name
     * @return string
     */
    public function getfirstname() {
        return $this->firstname;
    }

    /**
     * return user last name
     * @return string
     */
    public function getlastname() {
        return $this->lastname;
    }

    /**
     * return user email
     * @return string
     */
    public function getemail() {
        return $this->email;
    }

    /**
     * return path to etutorium connection
     * @param string $pathname
     * @return string
     */
    public function returnpath($pathname) {
        $path = array(
            'getAccounts' => '/api/moodle/account-list',
            'getWebinars' => '/api/moodle/webinar-list',
            'getRegfields' => '/api/moodle/regfields',
            'webinarConnect' => '/api/moodle/participant',
        );
        return $path[$pathname];
    }

    /**
     * return fullpath to etutorium conenct
     * @param string $pathname
     * @return string
     */
    public function getpath($pathname) {
        return self::PROTOCOL.'://'.self::DOMAIN.$this->returnpath(str_replace('.php', '', $pathname));
    }
}
$dataconnect = new dataconnect();
