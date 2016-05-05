<div style="width: 400px; left: 50%; margin-left: -200px; position: absolute">
    <?php
    if (!empty($error)) { ?>
    <div style="font-weight: bold; color: #FF0000"><?= $data['error'] ?></div>
    <?php
    } else
        echo $result;
    ?>
</div>
