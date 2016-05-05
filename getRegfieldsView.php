<table style='width: 100%'>
    <tr>
        <td colspan="3" style='text-align: center;'><?= get_string ('FillFields','etutorium') ?></td>
    </tr>
    <tr>
        <td colspan="3" style='text-align: center;font-weight: bold; color: #FF0000;' id='regfielderror'></td>
    </tr>
<?php foreach ($data['data']['regfields'] as $field){ ?>
    <tr>
        <td style='width:40%; text-align: right;'><?= $field['field_name']; ?><?= required($field['required']); ?></td>
        <td style='width: 5%;'></td>
        <td style='width: 55%; text-align: left;'><?= typefield($field); ?></td>
    </tr>
    <?php } 
    if ($data['countrequired'] > 0) {
        ?>
    <tr>
        <td colspan="3" style='text-align: left;'><?= required(true).' - '.get_string ('requiredelement','form'); ?></td>
    </tr>
    <?php
    }
?>
    <tr>
        <td colspan="3" style='text-align: center;'><input type='button' value="<?= get_string('connect','etutorium'); ?>" onclick="webinarconnect.webinarConnect('<?= $data['webinarid']; ?>')"></td>
    </tr>
</table>
<script type="text/javascript">
    var webinarconnect;
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", function() {
            webinarconnect = new ConnectJs('<?= $data['etutorium']->apikey; ?>', '<?= $data['etutorium']->id ?>');
            webinarconnect.fieldlist = ['<?= implode('\', \'', $data['fields']); ?>'];
            webinarconnect.requiredfieldlist = ['<?= implode('\', \'', $data['requiredfields']); ?>'];
            webinarconnect.count = <?= $data['countrequired']; ?>;
        });
    } else {
        window.onload = function(){
            webinarconnect = new ConnectJs('<?= $data['etutorium']->apikey; ?>', '<?= $data['etutorium']->id ?>');
            webinarconnect.fieldlist = ['<?= implode('\', \'', $data['fields']); ?>'];
            webinarconnect.requiredfieldlist = ['<?= implode('\', \'', $data['requiredfields']); ?>'];
            webinarconnect.count = <?= $data['countrequired']; ?>;
        };
    }
</script>
<?php

function required($required) {
    if ($required)
        return '<img class="req" title="'.get_string ('requiredelement','form').'" alt="'.get_string ('requiredelement','form').'" src="http://moodle24.com/theme/image.php/standard/core/1454501321/req">';
    else
        return '';
}

function typefield($field) {
    $return = '';
    switch (strtolower($field['field_type'])) {
        case 'area':
            $return = '<textarea maxlength="'.$field['field_length'].'" name="field'.$field['id'].'" id="field'.$field['id'].'">'.$field['value'].'</textarea>';
            break;
        default :
            $return = '<input type="text" maxlength="'.$field['field_length'].'" name="field'.$field['id'].'" id="field'.$field['id'].'" value="'.$field['value'].'" />';
            break;
    }
    return $return;
}