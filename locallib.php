<?php
function renderfile($view, $data=array(), $path=''){
    global $CFG;
    if (empty($path))
        $path = 'mod/'.basename(__DIR__);
    $filepath=$CFG->dirroot.'/'.$path.'/'.$view.'View.php';
    $content="";
    if (file_exists($filepath)){
        extract($data, EXTR_OVERWRITE);
        ob_start();
        include $filepath;
        $content = ob_get_contents();
        ob_end_clean();
    }
    return $content;
}

function renderjson($result, $error = ''){
    $resultarr = array(
        'error' => $error,
        'result' =>$result,
    );
    echo json_encode($resultarr);
    exit;
}