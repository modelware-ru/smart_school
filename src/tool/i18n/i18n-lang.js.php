// GENERATED [<?= date('Y-m-d H:i:s')?>]
import { el } from '../../../node_modules/redom/dist/redom.es';

export default {
<?php
foreach ($i18n_TTL as $key => $value) {
    $quote = $value['_type'] === 'plain' ? '`' : '';
    ?>
  TTL_<?=$key?>: (...args) => <?=$quote?><?=$value[$langId]?><?=$quote?>,
<?php
}
foreach ($i18n_MSG as $key => $value) {
    $quote = $value['_type'] === 'plain' ? '`' : '';
    ?>
  MSG_<?=$key?>: (...args) => <?=$quote?><?=$value[$langId]?><?=$quote?>,
<?php
}
foreach ($i18n_ERR as $key => $value) {
    $quote = $value['_type'] === 'plain' ? '`' : '';
    ?>
  ERR_<?=$key?>: (...args) => <?=$quote?><?=$value[$langId]?><?=$quote?>,
<?php
}
?>
};
