<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$id = optional_param('id', '', PARAM_INT);
$webinarid = optional_param('webinarid', '', PARAM_INT);

$etutorium = $DB->get_record('etutorium', array('id' => $id));
$webinar = $DB->get_record('etutoriumwebinars',array('webinar_id' => $webinarid, 'etutorium_id' => $etutorium->id));

if (empty($etutorium) || empty($webinar))
    renderjson ('', get_string('webinarnotfound', 'etutorium'));

if ($curl = curl_init()){
    $req = $_REQUEST;
    unset ($req['id']);
    unset ($req['webinarid']);
    $request = array_merge(array(
        'apikey' => $etutorium->apikey,
        'webinar_id' => $webinar->webinar_id,
        'email' => $dataconnect->getEmail(),
        'first_name' => $dataconnect->getFirstname(),
        'last_name' => $dataconnect->getLastname(),
    ), $req);

    curl_setopt($curl, CURLOPT_URL, $dataconnect->getPath(basename(__FILE__)));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    $out = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($out,true);
    
    if (isset ($json['ok'])) {
        if ($json['ok']) {
            renderjson($json['response'], '');
        } elseif(isset($json['error'])) {
            renderjson ('', implode('<br>', $json['error']));
        } elseif(isset($json['validate'])) {
            renderjson('', 'Error validate: '.  implode(', ', $json['validate']));
        }
    } else {
        renderjson ('',  (isset($json['message']))?$json['message']:'error');
    }
} else {
    renderjson ('', get_string('curlerror', 'etutorium'));
}



