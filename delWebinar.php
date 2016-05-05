<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', '', PARAM_INT);
$etutorium_id = optional_param('etutorium_id', '', PARAM_INT);

$DB->delete_records('etutoriumwebinars', array('id' => $id, 'etutorium_id' => $etutorium_id));
renderjson('ok');
