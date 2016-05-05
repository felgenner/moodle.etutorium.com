<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$webinar_id = optional_param('id', '', PARAM_INT);
$etutorium_id = optional_param('etutorium_id', '', PARAM_INT);
$title = optional_param('title', '', PARAM_TEXT);
$description = optional_param('description', '', PARAM_TEXT);
$start_time = optional_param('start_time', '', PARAM_TEXT);
$finish_time = optional_param('finish_time', '', PARAM_TEXT);

$record = $DB->get_records_sql('select id from {etutoriumwebinars} where webinar_id = '.$webinar_id.' and etutorium_id = '.$etutorium_id);
if (!empty($record))
    renderjson ('', get_string('webinar_exist', 'etutorium'));
else {
    $newwebinar = new stdClass();
    $newwebinar->etutorium_id = $etutorium_id;
    $newwebinar->webinar_id = $webinar_id;
    $newwebinar->title = $title;
    $newwebinar->description = $description;
    $newwebinar->start_time = (!empty($start_time) && (strtolower($start_time)) != 'null') ? $start_time : '0000-00-00 00:00:00';
    $newwebinar->finish_time = (!empty($finish_time) && (strtolower($finish_time)) != 'null') ? $finish_time : '0000-00-00 00:00:00';
    $DB->insert_record('etutoriumwebinars', $newwebinar);
    renderjson('ok');
}