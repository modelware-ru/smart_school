<?php
use MW\Shared\Util;

global $templateData;
global $langId;
?>
<!DOCTYPE html>
<html lang='<?= $langId ?>' data-bs-theme='auto'>
<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/guest_guestIndex_bundle.js' defer></script>
    <style>
        #main {
            max-width: 450px;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light" aria-label="Навигационная панель">
            <?= Util::RenderTemplate('app/template/shared/guest-navigator.php') ?>
        </nav>
        <hr class='m-0'/>
        <div id="main" class="d-flex flex-column mx-auto shadow-lg my-3">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>