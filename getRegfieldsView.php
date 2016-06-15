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
<table style='width: 100%'>
    <tr>
        <td colspan="3" style="text-align: center;"><?php echo get_string ('FillFields', 'etutorium') ?></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;font-weight: bold; color: #FF0000;" id="regfielderror"></td>
    </tr>
<?php foreach ($data['data']['regfields'] as $field) { ?>
    <tr>
        <td style="width:40%; text-align: right;">
            <?php echo $field['field_name']; ?><?php echo required($field['required']); ?>
        </td>
        <td style="width: 5%;"></td>
        <td style="width: 55%; text-align: left;"><?php echo typefield($field); ?></td>
    </tr>
    <?php
}
if ($data['countrequired'] > 0) {
        ?>
    <tr>
        <td colspan="3" style="text-align: left;"><?php echo required(true).' - '.get_string ('requiredelement', 'form'); ?></td>
    </tr>
    <?php
}
?>
    <tr>
        <td colspan="3" style="text-align: center;">
            <input type="button" value="<?php
            echo get_string('connect', 'etutorium'); ?>" onclick="webinarconnect.webinarConnect('<?php
            echo $data['webinarid']; ?>')">
        </td>
    </tr>
</table>
<script type="text/javascript">
    var webinarconnect;
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', function() {
            webinarconnect = new ConnectJs('<?php echo $data['etutorium']->apikey; ?>', '<?php echo $data['etutorium']->id ?>');
            webinarconnect.fieldlist = ['<?php echo implode('\', \'', $data['fields']); ?>'];
            webinarconnect.requiredfieldlist = ['<?php echo implode('\', \'', $data['requiredfields']); ?>'];
            webinarconnect.count = <?php echo $data['countrequired']; ?>;
        });
    } else {
        window.onload = function(){
            webinarconnect = new ConnectJs('<?php echo $data['etutorium']->apikey; ?>', '<?php echo $data['etutorium']->id ?>');
            webinarconnect.fieldlist = ['<?php echo implode('\', \'', $data['fields']); ?>'];
            webinarconnect.requiredfieldlist = ['<?php echo implode('\', \'', $data['requiredfields']); ?>'];
            webinarconnect.count = <?php echo $data['countrequired']; ?>;
        };
    }
</script>
<?php

function required($required) {
    if ($required) {
        return '<img class="req" title="'.get_string ('requiredelement', 'form').'" alt="'.
        get_string ('requiredelement', 'form').'" src="http://moodle24.com/theme/image.php/standard/core/1454501321/req">';
    } else {
        return '';
    }
}

function typefield($field) {
    $return = '';
    switch (strtolower($field['field_type'])) {
        case 'area':
            $return = '<textarea maxlength="'.$field['field_length'].'" name="field'.$field['id'].'" id="field'.$field['id'].'">'.
            $field['value'].'</textarea>';
            break;
        default :
            $return = '<input type="text" maxlength="'.$field['field_length'].'" name="field'.$field['id'].'" id="field'.
            $field['id'].'" value="'.$field['value'].'" />';
            break;
    }
    return $return;
}