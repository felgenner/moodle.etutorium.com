<?php $data['data'] = json_decode(json_encode($data['data']),true); ?>
<div style='float: left; width: 100%;' id='<?= $data['id']; ?>'>
    <table style='width: 100%' class="admintable generaltable">
        <tr>
            <th class="header" style="width: 10%;"><?= get_string('ID', 'etutorium'); ?></th>
            <th class="header"><?= get_string('title', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?php if ($data['id'] == 'allweblist') { 
                echo get_string('status', 'etutorium');
            } ?></th>
            <th class="header" style="width: 10%;"></th>
        </tr><?php 
        if (empty($data['data'])) {
            ?>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; color: #0000ff"><?= ($data['action']=='add') ? get_string('webinarsnotfound', 'etutorium') : get_string('addwebnotfound', 'etutorium'); ?></td>
        </tr><?php
        } else {
            $current_time = new DateTime(date('Y-m-d'));
            foreach ($data['data'] as $webinar) {
                $d1=new DateTime($webinar['start_time']);
                $diff=$current_time->diff($d1);
        ?>
        <tr id="<?= $data['id']; ?><?= $webinar['id']; ?>">
            <td style='text-align: center; cursor:pointer;' onclick='webinaredit.viewmore(<?= $webinar['id']; ?>, "<?= $data['id']; ?>")'><?= $webinar['id']; ?></td>
            <td style='cursor:pointer;' onclick='webinaredit.viewmore(<?= $webinar['id']; ?>, "<?= $data['id']; ?>")'><?= $webinar['title']; ?></td>
            <?php if ($data['id'] == 'allweblist') {
                ?><td style='text-align: center; cursor:pointer;' onclick='webinaredit.viewmore(<?= $webinar['id']; ?>, "<?= $data['id']; ?>")'><?= get_string($webinar['status'], 'etutorium'); ?></td><?php
            } else {
                ?><td style='text-align: center; cursor:pointer;' onclick='webinaredit.viewmore(<?= $webinar['id']; ?>, "<?= $data['id']; ?>")'><a href="./connect.php?webinar_id=<?= $webinar['webinar_id']; ?>&id=<?= $data['etutorium_id']; ?>" target="_blank"><?= (!$diff->invert)?get_string('connect','etutorium'):get_string('get_archive','etutorium'); ?></a></td><?php
            } ?>
            <td style='text-align: center;'><input type="button" value=" <?= ($data['action']=='add') ? '+' : '-'; ?> " onclick="webinaredit.<?= $data['action']; ?>Webinar(<?= $webinar['id']; ?>)"></td>
        </tr>
        <?php }
        } ?>
    </table>
</div>
<div style='float: left; width: 0%;' id='<?= $data['id']; ?>moreinfo' style='display: none;'>
    
</div>
<div style='clear: left;'></div>
