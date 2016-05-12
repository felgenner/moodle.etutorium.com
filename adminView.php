<?php
include_once __DIR__.'/datepickermini.php';
?>
<h2><?= $etutorium->name ?></h2>
<div style='height:15px;'>&nbsp;</div>
<div><?= $etutorium->intro ?></div>
<div style='height:15px;'>&nbsp;</div>
<div id='usewebinarlist'>
<?php
echo $result = renderfile('getWebinars', array('data' => array('data' => $userweblist,'id' => 'userweblist','action'=>'del','apikey'=>$etutorium->apikey, 'etutorium_id'=>$etutorium->id)));
?>
</div>
<script type="text/javascript">
    var webinaredit;
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", function() {
            webinaredit = new AdminJs('<?= $etutorium->apikey; ?>', <?= $etutorium->id; ?>);
            webinaredit.userweblist= [<?php
            $u = array();
    foreach($userweblist as $value){
        $u[] = json_encode($value);
    }
    echo implode(',', $u);
    ?>];
        });
    } else {
        window.onload = function(){ 
            webinaredit = new AdminJs('<?= $etutorium->apikey; ?>', <?= $etutorium->id; ?>);
        };
    }
</script>
<div style='height:15px;'>&nbsp;</div>
<div id='error' style="font-weight:bold; color:#ff0000; text-align:center;"></div>
<div style='height:15px;'>&nbsp;</div>
<div>
    <div id="viewsearch" style="text-align: center; cursor: pointer;" onclick="webinaredit.viewpanel()"><?= get_string('searchpanel', 'etutorium'); ?></div>
    <div id="searchpanel" style="display: none;">
        <div>
            <span><input type="checkbox" id="useaccount"></span>
            <span>Accounts:</span>
            <span id="account"></span>
        </div>
        <div>
            <span><input type="checkbox" id="usestart_time"></span>
            <span><?= get_string('start_time','etutorium') ?>:</span>
            <span><?php
            $exam_date=new etutorium_datepickermini();
            echo $exam_date->display('start_time', true);
            ?></span>
        </div>
        <div>
            <span><input type="checkbox" id="usefinish_time"></span>
            <span><?= get_string('finish_time', 'etutorium') ?>:</span>
            <span><?php
            $exam_date=new etutorium_datepickermini();
            echo $exam_date->display('finish_time', true);
            ?></span>
        </div>
    </div>
</div>
<div style='height:15px;'>&nbsp;</div>
<div style='text-align:center;'><input type='button' value='<?= get_string('findwebinar','etutorium'); ?>' onclick="webinaredit.getallwebinarlist()"></div>
<div style='height:15px;'>&nbsp;</div>
<div id='allwebinarlist'></div>
<script type="text/javascript">
    allweblist=[];
</script>