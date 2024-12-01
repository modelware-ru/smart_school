<?php
global $userName;
?>
<a class="navbar-brand" href="index.php">Квадривиум [T]</a>
<span><?= $userName ?></span>
<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Навигация">
    <span class="navbar-toggler-icon"></span>
</button>

<div id="navbarMain" class="navbar-collapse collapse justify-content-end">
    <ul class="navbar-nav mb-2 mb-md-0 gap-md-4">
        <!-- <li class="nav-item"><span role="button" class="nav-link active btn btn-light" onclick="window.close();">Закрыть окно</span></li> -->
        <li class="nav-item">
            <button role="button" class="nav-link active btn btn-light" onclick="window.close();">
                <span role="status">Закрыть окно</span>
                <i class="bi bi-x-lg ms-3"></i>
            </button>
        </li>
    </ul>
</div>