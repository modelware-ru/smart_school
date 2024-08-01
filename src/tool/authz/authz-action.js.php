// GENERATED [<?=date('Y-m-d H:i:s')?>]
export default {
<?php
foreach ($actionList as $value) {
?>
    ACTION_<?= strtoupper($value['code_name']) ?>: <?= $value['id'] ?>, // <?= $value['name'] . PHP_EOL ?>
<?php
}
?>
};
