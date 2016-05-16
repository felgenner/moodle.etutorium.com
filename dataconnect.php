<?php
class dataconnect
{
    const PROTOCOL = 'https';
    const DOMAIN = 'api.etutorium.com';

    var $firstname = '';
    var $lastname = '';
    var $email = '';

    function __construct() {
        global $USER;
        $this->firstname = (!empty($USER->firstname)) ? $USER->firstname : '';
        $this->lastname = (!empty($USER->lastname)) ? $USER->lastname : '';
        $this->email = $USER->email;
    }

    function getFirstname() {
        return $this->firstname;
    }

    function getLastname() {
        return $this->lastname;
    }

    function getEmail() {
        return $this->email;
    }

    function returnpath($pathname)
    {
        $path = array(
            'getAccounts' => '/api/moodle/account-list',
            'getWebinars' => '/api/moodle/webinar-list',
            'getRegfields' => '/api/moodle/regfields',
            'webinarConnect' => '/api/moodle/participant',
        );
        return $path[$pathname];
    }

    function getPath($pathname)
    {
        return self::PROTOCOL.'://'.self::DOMAIN.$this->returnpath(str_replace('.php', '', $pathname));
    }
}
$dataconnect = new dataconnect();