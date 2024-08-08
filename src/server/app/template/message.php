<?php
use MW\Shared\MWI18nHelper;
use MW\Shared\Util;

global $templateData;
global $langId;

$templateData['_js']['message'] = $templateData['message'];

$resource = $templateData['resource'];
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>
<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/<?=$resource?>_bundle.js' defer></script>
</head>
<body>
    <div class="container">
        <nav id="nav" class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/guestNavigator.php') ?>
        </nav>
        <hr class='m-0'/>
        <div id="main" class="d-flex flex-column mx-auto my-3">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>