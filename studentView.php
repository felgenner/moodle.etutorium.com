<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @author Petrina Alexandr <info@aktivcorp.com>
 * @copyright (c) 2016, Aktive Corporation
 * @version 1.0
 * @package mod/etutorium
 */
?>
<h2><?php echo $etutorium->name ?></h2>
<div style="height:15px;">&nbsp;</div>
<div><?php echo $etutorium->intro ?></div>
<div style="height:15px;">&nbsp;</div>
<script type="text/javascript">
    var webinarconnect;
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', function() {
            webinarconnect = new ConnectJs('<?php echo $etutorium->apikey; ?>', '<?php echo $etutorium->id ?>');
        });
    } else {
        window.onload = function(){
            webinarconnect = new ConnectJs('<?php echo $etutorium->apikey; ?>', '<?php echo $etutorium->id ?>');
        };
    }
</script>
<div id="usewebinarlist">
    <table style="width: 100%;" class="admintable generaltable">
        <tr>
            <th class="header" style="width: 45%"><?php echo get_string('title', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?php echo get_string('start_time', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?php echo get_string('finish_time', 'etutorium'); ?></th>
            <th class="header" style="width: 15%;"></th>
        </tr><?php
if (empty($userweblist)) {
            ?>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; color: #0000ff"><?php
            echo get_string('addwebnotfound', 'etutorium'); ?></td>
        </tr><?php
} else {
    $currenttime = new DateTime(date('Y-m-d'));
    foreach ($userweblist as $webinar) {
        $d1 = new DateTime($webinar->start_time);
        $diff = $currenttime->diff($d1);
        ?>
        <tr id="<?php echo $webinar->id; ?>">
            <td><?php echo $webinar->title; ?></td>
            <td><?php echo $webinar->start_time; ?></td>
            <td><?php echo (!empty($webinar_finish_time))?$webinar->finish_time:get_string('finish_time_undefined', 'etutorium'); ?></td>
            <td style="text-align: center;">
                <a href="./connect.php?webinar_id=<?php echo $webinar->webinar_id; ?>&id=<?php
                echo $etutorium->id; ?>" target="_blank" id="link<?php echo $webinar->webinar_id; ?>">
                <?php echo (!$diff->invert) ? get_string('connect', 'etutorium') : get_string('get_archive', 'etutorium'); ?>
                </a>
            </td>
        </tr><?php
    }
} ?>
    </table>
</div>
<style>
    .regfields {
        width: 500px;
        left: 50%;
        margin-left: -250px;
        background-color: #ffffff;
        position: fixed;
        top: 30%;
        padding: 10px;
        border: solid #000000 1px;
    }
</style>
<div id="regfields" style="display: none;">

</div>
