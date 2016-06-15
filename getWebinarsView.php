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

$data['data'] = json_decode(json_encode($data['data']), true);
?>
<div style="float: left; width: 100%;" id="<?php echo $data['id']; ?>">
    <table style="width: 100%" class="admintable generaltable">
        <tr>
            <th class="header" style="width: 10%;"><?php echo get_string('ID', 'etutorium'); ?></th>
            <th class="header"><?php echo get_string('title', 'etutorium'); ?></th>
            <th class="header" style="width: 20%;"><?php if ($data['id'] == 'allweblist') {
                echo get_string('status', 'etutorium');
} ?></th>
            <th class="header" style="width: 10%;"></th>
        </tr><?php 
if (empty($data['data'])) {
            ?>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; color: #0000ff">
            <?php echo ($data['action'] == 'add') ?
            get_string('webinarsnotfound', 'etutorium') : get_string('addwebnotfound', 'etutorium'); ?>
            </td>
        </tr><?php
} else {
            $currenttime = new DateTime(date('Y-m-d'));
    foreach ($data['data'] as $webinar) {
            $d1 = new DateTime($webinar['start_time']);
            $diff = $currenttime->diff($d1);
        ?>
        <tr id="<?php echo $data['id']; ?><?php echo $webinar['id']; ?>">
            <td style="text-align: center; cursor:pointer;" onclick="webinaredit.viewmore(<?php
            echo $webinar['id']; ?>, '<?php echo $data['id']; ?>')">
            <?php echo $webinar['id']; ?>
            </td>
            <td style="cursor:pointer;" onclick="webinaredit.viewmore(<?php echo $webinar['id']; ?>, '<?php echo $data['id']; ?>')">
            <?php echo $webinar['title']; ?>
            </td>
            <?php 
        if ($data['id'] == 'allweblist') {
?><td style='text-align: center; cursor:pointer;' onclick="webinaredit.viewmore(<?php
                echo $webinar['id']; ?>, '<?php echo $data['id']; ?>')">
                <?php echo get_string($webinar['status'], 'etutorium'); ?>
                </td><?php
        } else {
                ?><td style="text-align: center; cursor:pointer;" onclick="webinaredit.viewmore(<?php
                echo $webinar['id']; ?>, '<?php echo $data['id']; ?>')">
                    <a href="./connect.php?webinar_id=<?php echo $webinar['webinar_id']; ?>&id=<?php
                    echo $data['etutorium_id']; ?>" target="_blank">
                    <?php echo (!$diff->invert) ? get_string('connect', 'etutorium') : get_string('get_archive', 'etutorium'); ?>
                    </a>
                </td><?php
        } ?>
            <td style="text-align: center;">
                <input type="button" value=" <?php 
                echo ($data['action'] == 'add') ? '+' : '-'; ?> " onclick="webinaredit.<?php
                echo $data['action']; ?>Webinar(<?php echo $webinar['id']; ?>)">
            </td>
        </tr>
        <?php
    }
} ?>
    </table>
</div>
<div style="float: left; width: 0%;" id="<?php echo $data['id']; ?>moreinfo" style="display: none;">
    
</div>
<div style="clear: left;"></div>
