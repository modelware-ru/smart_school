<?php
use MW\Shared\Util;

global $templateData;
global $langId;
?>
<!DOCTYPE html>
<html lang='<?=$langId?>' data-bs-theme='auto'>

<?=Util::RenderTemplate('app/template/shared/head.php')?>

<body>
    <div class='container'>
        <nav id='nav' class='navbar navbar-expand-md navbar-light' aria-label='Эврика навигационная панель'></nav>
        <hr class='m-0'/>
        GUEST INDEX
        <!-- <div id='main' class='d-flex align-items-center py-4'></div> -->
    </div>
    <script src='js/bootstrap.bundle.min.js'></script>
</body>

</html>