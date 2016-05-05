<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$webinar_id = optional_param('webinar_id', '', PARAM_INT);
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
    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'webinar_id' => $webinar_id,
        'apikey' => $apikey,
        'email' => $USER->email,
    ));
    $out = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($out,true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {
            $fields = array();
            $requiredfield = array();
            foreach ($json['response']['regfields'] as $field){
                $fields[] = 'field'.$field['id'];
                if ($field['required'])
                    $requiredfield[] = 'field'.$field['id'];
            }
            if (!empty($json['response']['regfields'])) {
                $result = renderfile('getRegfields', array(
                    'data' => array(
                        'data' => $json['response'],
                        'apikey'=>$apikey,
                        'countrequired' => count($requiredfield),
                        'webinarid' => $webinar_id
                        )
                    ));
            } else
                $result = 'ok';
            renderjson([
                'table' => $result,
                'fieldlist' => $fields,
                'requiredfieldlist' => $requiredfield,
                'count' => count($json['response']['regfields']),
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