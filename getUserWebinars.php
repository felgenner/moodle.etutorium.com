<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('etutorium', '', PARAM_INT);
$apikey = optional_param('apikey', '', PARAM_TEXT);

$usewebinar = $DB->get_records('etutoriumwebinars',array('etutorium_id' => $id));
$u=array();
foreach($usewebinar as $value){
    $u[] = $value;
}
//echo renderfile('getWebinars', array('data' => array('data' => $u,'id' => 'userweblist','action'=>'del','apikey'=>$apikey)));
//renderjson(renderfile('getWebinars', array('data' => array('data' => $u,'id' => 'userweblist','action'=>'del','apikey'=>$apikey))));
renderjson([
    'table' => renderfile('getWebinars', array('data' => array('data' => $u,'id' => 'userweblist','action'=>'del','apikey'=>$apikey, 'etutorium_id' => $id))),
    'id' => 'userweblist',
    'data' => $u,
], '');