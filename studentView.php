<h2><?= $etutorium->name ?></h2>
<div style='height:15px;'>&nbsp;</div>
<div><?= $etutorium->intro ?></div>
<div style='height:15px;'>&nbsp;</div>
<script type="text/javascript">
    var webinarconnect;
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", function() {
            webinarconnect = new ConnectJs('<?= $etutorium->apikey; ?>', '<?= $etutorium->id ?>');
        });
    } else {
        window.onload = function(){
            webinarconnect = new ConnectJs('<?= $etutorium->apikey; ?>', '<?= $etutorium->id ?>');
        };
    }
</script>
<div id='usewebinarlist'>
    <table style="width: 100%;" class="admintable generaltable">
        <tr>
            <th class="header" style="width: 45%"><?= get_string('title', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?= get_string('start_time', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?= get_string('finish_time', 'etutorium'); ?></th>
            <th class="header" style="width: 15%;"></th>
        </tr><?php
        if (empty($userweblist)) {
            ?>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; color: #0000ff"><?= get_string('addwebnotfound', 'etutorium'); ?></td>
        </tr><?php
        } else {
            $current_time = new DateTime(date('Y-m-d'));
            foreach ($userweblist as $webinar) {
                $d1=new DateTime($webinar->start_time);
                $diff=$current_time->diff($d1);
        ?>
        <tr id="<?= $webinar->id; ?>">
            <td><?= $webinar->title; ?></td>
            <td><?= $webinar->start_time; ?></td>
            <td><?= $webinar->finish_time; ?></td>
            <td style="text-align: center;">
                <a href="./connect.php?webinar_id=<?= $webinar->webinar_id; ?>&id=<?= $etutorium->id; ?>" target="_blank" id="link<?= $webinar->webinar_id; ?>"><?= (!$diff->invert)?get_string('connect','etutorium'):get_string('get_archive','etutorium'); ?></a>
            </td>
        </tr><?php }
        } ?>
    </table>
</div>
<div id='regfields' style='width:500px;left: 50%;margin-left: -250px;background-color: #ffffff; position: fixed;top: 30%;padding: 10px; border: solid #000000 1px;display: none;'>

</div>
