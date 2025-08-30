<?php
use MW\Shared\Util;
?>
<!DOCTYPE html>
<html lang='ru' data-bs-theme='auto'>

<head>
    <?= Util::RenderTemplate('app/template/shared/head.php') ?>
    <script type='text/javascript' src='js/test_bundle.js' defer></script>
</head>

<body>
    <div class="container">
        <div id="main" class="d-flex flex-column mx-auto my-3">
        </div>
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>