<?php

require_once(dirname(__FILE__).'/locallib.php');

class dataconnect
{
    const PROTOCOL = 'https';
    const DOMAIN = 'api.etutorium.com';

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