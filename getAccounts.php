<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$apikey = optional_param('apikey', '', PARAM_TEXT);

if ($curl = curl_init()){
    curl_setopt($curl, CURLOPT_URL, $dataconnect->getPath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('apikey' => $apikey));
    $out = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($out,true);
    if (isset ($json['ok'])) {
        if ($json['ok']) {
            renderjson($json['response']['accounts'], '');
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
