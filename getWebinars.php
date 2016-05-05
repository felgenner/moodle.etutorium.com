<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$id = optional_param('etutorium', '', PARAM_INT);
$apikey = optional_param('apikey', '', PARAM_TEXT);

$result = '';
$error = '';

if ($curl = curl_init()){
    curl_setopt($curl, CURLOPT_URL, $dataconnect->getPath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $_REQUEST);
    $out = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($out,true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {

            foreach ($json['response'] as $key=>$value) {
                $json['response'][$key]['start_time'] = changetimewithtimezone($value['start_time'], $value['timezone']);
                if (!empty($value['finish_time']))
                    $json['response'][$key]['finish_time'] = changetimewithtimezone($value['finish_time'], $value['timezone']);
            }

            $exist = $DB->get_records_sql('select webinar_id from {etutoriumwebinars} where etutorium_id='.$id);
            $date = date('Y-m-d');
            foreach ($json['response'] as $key=>$value) {
                if ($value['start_time'] < $date) {
                    $json['response'][$key]['status'] = 'past';
                } else {
                    $json['response'][$key]['status'] = 'future';
                }
                foreach ($exist as $existvalue){ //убираем уже существующие записи
                    if ($existvalue->webinar_id == $value['id'])
                        unset ($json['response'][$key]);
                }
            }
            $result = renderfile('getWebinars', array('data' => array('data' => $json['response'],'id' => 'allweblist','action'=>'add','apikey'=>$apikey, 'etutorium_id' => $id)));
            renderjson([
                'table' => $result,
                'id' => 'allweblist',
                'data' => $json['response'],
            ], $error);
        } elseif(isset($json['error'])) {
            renderjson ('', implode('<br>', $json['error']));
        } elseif(isset($json['validate'])) {
            renderjson('', 'Error validate: '.  implode(', ', $json['validate']));
        }
    } else {
        $error = (isset($json['message']))?$json['message']:'error';
    }
} else {
    $error = get_string('curlerror', 'etutorium');
}

renderjson($result, $error);

function changetimewithtimezone($time, $timezone){
    $start_time = new DateTime($time, new DateTimeZone($timezone));
    $matches = null;
    if (preg_match('/(\+|\-)([0-9]{2})\:([0-9]{2})/', $start_time->format('P'), $matches)) {
        $interval = new DateInterval('PT'.(int)$matches[2].'H'.(int)$matches[3].'M');
        return $start_time->add($interval)->format('Y-m-d H:i:s');
    } else
        return $time;
}