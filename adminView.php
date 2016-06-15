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

require_once(__DIR__.'/datepickermini.php');
?>
<h2><?php echo $etutorium->name ?></h2>
<div style="height:15px;">&nbsp;</div>
<div><?php echo $etutorium->intro ?></div>
<div style="height:15px;">&nbsp;</div>
<div id='usewebinarlist'>
<?php
echo $result = renderfile('getWebinars', array(
    'data' => array(
        'data' => $userweblist,
        'id' => 'userweblist',
        'action' => 'del',
        'apikey' => $etutorium->apikey,
        'etutorium_id' => $etutorium->id)
    ));
?>
</div>
<script type="text/javascript">
    var webinaredit;
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', function() {
            webinaredit = new AdminJs('<?php echo $etutorium->apikey; ?>', <?php echo $etutorium->id; ?>);
            webinaredit.userweblist= [<?php
            $u = array();
foreach ($userweblist as $value) {
    $u[] = json_encode($value);
}
    echo implode(',', $u);
    ?>];
        });
    } else {
        window.onload = function() {
            webinaredit = new AdminJs('<?php echo $etutorium->apikey; ?>', <?php echo $etutorium->id; ?>);
        };
    }
</script>
<div style="height:15px;">&nbsp;</div>
<div id="error" style="font-weight:bold; color:#ff0000; text-align:center;"></div>
<div style="height:15px;">&nbsp;</div>
<div>
    <div id="viewsearch" style="text-align: center; cursor: pointer;" onclick="webinaredit.viewpanel()">
            <?php echo get_string('searchpanel', 'etutorium'); ?>
    </div>
    <div id="searchpanel" style="display: none;">
        <div>
            <span><input type="checkbox" id="useaccount"></span>
            <span>Accounts:</span>
            <span id="account"></span>
        </div>
        <div>
            <span><input type="checkbox" id="usestart_time"></span>
            <span><?php echo get_string('start_time', 'etutorium') ?>:</span>
            <span><?php
            $examdate = new etutorium_datepickermini();
            echo $examdate->display('start_time', true);
            ?></span>
        </div>
        <div>
            <span><input type="checkbox" id="usefinish_time"></span>
            <span><?php echo get_string('finish_time', 'etutorium') ?>:</span>
            <span><?php
            $examdate = new etutorium_datepickermini();
            echo $examdate->display('finish_time', true);
            ?></span>
        </div>
    </div>
</div>
<div style="height:15px;">&nbsp;</div>
<div style="text-align:center;">
    <input type="button" value="<?php echo get_string('findwebinar', 'etutorium'); ?>" onclick="webinaredit.getallwebinarlist()">
</div>
<div style="height:15px;">&nbsp;</div>
<div id="allwebinarlist"></div>
<script type="text/javascript">
    allweblist = [];
</script>