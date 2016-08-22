<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');
require_once(dirname(__FILE__).'/locallib.php');

if (!etutorium_ispost()) {
    die;
}

$action = required_param('action', PARAM_TEXT);

$usefile = dirname(__FILE__).'/'.$action.'.php';

if (file_exists($usefile)) {
    require_once($usefile);
} else {
    etutorium_renderjson ('', 'File not found');
}