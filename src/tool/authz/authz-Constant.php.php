<?php
echo '<?php' . PHP_EOL;
?>
// GENERATED [<?=date('Y-m-d H:i:s')?>]
namespace MW\Service\Authz;

class Constant {

    // Permission
    const PERMISSION_ALLOW = 'ALLOW';
    const PERMISSION_PROHIBIT = 'PROHIBIT';

    // Role ID
<?php
foreach ($roleList as $value) {
    ?>
    const ROLE_<?=strtoupper($value['code_name'])?>_ID = <?=$value['id']?>; // <?=$value['name'] . PHP_EOL?>
<?php
}
?>
    // Role State
<?php
foreach ($roleStateList as $value) {
?>
    const ROLE_STATE_<?=strtoupper($value['role_code_name'])?>_<?=strtoupper($value['state_code_name'])?>_ID = <?=strtoupper($value['state_id'])?>; // <?=$value['role_name'] . ' : ' . $value['state_name'] . PHP_EOL?>
<?php
}
?>

    const ROLE_STATE = [
<?php
$id = 0;
foreach ($roleStateList as $value) {
    if (intval($value['id']) !== $id) {
        if ($id !== 0) {
?>
        ],
<?php
        }
        $id = $value['id'];
?>
        self::ROLE_<?=strtoupper($value['role_code_name'])?>_ID => [
<?php
    }
?>
            self::ROLE_STATE_<?=strtoupper($value['role_code_name'])?>_<?=strtoupper($value['state_code_name'])?>_ID,
<?php
}
if ($id !== 0) {
?>
        ],
<?php
}    
?>
    ];

    // Resource Type
<?php
foreach ($resourceTypeList as $value) {
    ?>
    const RESOURCE_TYPE_<?=$value['code_name']?> = '<?=$value['resource_type']?>'; <?=PHP_EOL?>
<?php
}
?>

    // Resources
<?php
foreach ($resourceList as $name => $resource) {
    ?>
    // <?=$name . PHP_EOL?>
<?php
    foreach ($resource as $key => $value) {
?>
    const RESOURCE_<?=$name ?>_<?=strtoupper($value['code_name'])?> = '<?=$value['code_name']?>'; <?=PHP_EOL?>
<?php
    }
}
?>

    // Action
<?php
foreach ($actionList as $value) {
?>
    const ACTION_<?= strtoupper($value['code_name']) ?> = <?= $value['id'] ?>; // <?= $value['name'] . PHP_EOL ?>
<?php
}
?>

    public static function CheckRoleStateId($roleId, $roleStateId) {
        return in_array($roleStateId, self::ROLE_STATE[$roleId]);
    }
}