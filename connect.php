<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/dataconnect.php');

$webinar_id = optional_param('webinar_id', '', PARAM_INT);
$id = optional_param('id', '', PARAM_INT);

$etutorium = $DB->get_record('etutorium', array('id' => $id));
$webinar = $DB->get_record('etutoriumwebinars',array('webinar_id' => $webinar_id, 'etutorium_id' => $etutorium->id));

if (empty($etutorium) || empty($webinar))
    $error = get_string('webinarnotfound', 'etutorium');

function getregfield($webinar, $etutorium, $dataconnect) {
    global $USER;
    $result = '';
    $error = '';
    
    if ($curl = curl_init()){
        curl_setopt($curl, CURLOPT_URL, $dataconnect->getPath('getRegfields'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'webinar_id' => $webinar->webinar_id,
            'apikey' => $etutorium->apikey,
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
                            'apikey'=>$etutorium->apikey,
                            'countrequired' => count($requiredfield),
                            'webinarid' => $webinar->webinar_id,
                            'requiredfields' => $requiredfield,
                            'fields' => $fields,
                            'etutorium' => $etutorium
                            )
                        ));
                } else {
                    list($result, $error) = webinarConnect($webinar, $etutorium, $dataconnect);
                }
            } elseif(isset($json['error'])) {
                $error =  implode('<br>', $json['error']);
            } elseif(isset($json['validate'])) {
                $error = 'Error validate: '.  implode(', ', $json['validate']);
            }
        } else {
            $error = (isset($json['message']))?$json['message']:'error';
        }
    } else {
        $error = get_string('curlerror', 'etutorium');
    }

    return array($result, $error);
}

function webinarConnect($webinar, $etutorium, $dataconnect){
    global $USER;
    $result = '';
    $error = '';
    if ($curl = curl_init()){
        $req = $_REQUEST;
        unset ($req['id']);
        unset ($req['webinar_id']);
        $request = array_merge(array(
            'apikey' => $etutorium->apikey,
            'webinar_id' => $webinar->webinar_id,
            'email' => $dataconnect->getEmail(),
            'first_name' => $dataconnect->getFirstname(),
            'last_name' => $dataconnect->getLastname(),
        ), $req);

        curl_setopt($curl, CURLOPT_URL, $dataconnect->getPath('webinarConnect'));
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
                if (headers_sent()) {
                    $result = '<script> document.location.href = \''.$json['response']['authurl'].'\'; </script>';
                } else {
                    header( 'Location: '.$json['response']['authurl'] );
                }
            } elseif(isset($json['error'])) {
                $error = implode('<br>', $json['error']);
            } elseif(isset($json['validate'])) {
                $error = 'Error validate: '.  implode(', ', $json['validate']);
            }
        } else {
            $error = (isset($json['message']))?$json['message']:'error';
        }
    } else {
        $error = get_string('curlerror', 'etutorium');
    }

    return array($result, $error);
}

list($result, $error) = getregfield($webinar, $etutorium, $dataconnect);

if (! $etutorium = $DB->get_record('etutorium', array('id' => $id))) {
    error('Course module is incorrect');
}
if (! $course = $DB->get_record('course', array('id' => $etutorium->course))) {
    error('Course is misconfigured');
}
if (! $cm = get_coursemodule_from_instance('etutorium', $etutorium->id, $course->id)) {
    error('Course Module ID was incorrect');
}

require_login($course, true, $cm);
$PAGE->set_url('/mod/etutorium/view.php', array('webinar_id' => $webinar_id, 'id' => $id));
$PAGE->requires->js('/mod/etutorium/js/send.js');
$PAGE->requires->js('/mod/etutorium/js/connect.js');
$PAGE->requires->strings_for_js(array(
    'fullingfields',
    'participant-webinar-not-found',
), 'etutorium');

echo $OUTPUT->header();
echo renderfile('connect', array('result' => $result, 'error' => $error, 'etutorium' => $etutorium));
echo $OUTPUT->footer();