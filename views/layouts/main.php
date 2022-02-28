<?php

use app\core\Application;

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--    Google Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <!--    Lib-->
    <script src="https://kit.fontawesome.com/8f2e385c2e.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.min.js"></script>
    <title><?= $this->title ?></title>
</head>
<body>

<!--navbar-->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #026AA7; position: fixed; width: 100%;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img style="width: 35px" src="/img/site/main_logo.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
                aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse show" id="navbarColor01" style="">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/profile">Profiles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white"
                       href="/logout">Hi, <?php echo Application::$app->user->getDisplayName () ?>
                        (Logout)</a>
                </li>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<div style="height: 80px" class="cuz_nav_is_fixed"></div>

<div class="container">
    <?php
    if (Application::$app->session->getFlash ('success')):?>
        <div class="alert alert-success" style="margin-top: 10px">
            <?= Application::$app->session->getFlash ('success') ?>
        </div>
    <?php endif; ?>
    <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem; position: fixed; left: 0; top: 27%">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <li class="nav-item">
                <a href="/new-project" class="nav-link py-3 border-bottom" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Create new project">
                    <i class="fa-solid fa-folder-plus""></i>
                </a>
            </li>
            <li>
                <a href="/list-project" class="nav-link py-3 border-bottom" title="" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   data-bs-original-title="My Project">
                    <i class="fas fa-list-ul"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom" title="" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   data-bs-original-title="Doing">
                    <i class="fas fa-stream"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom" title="" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   data-bs-original-title="Deadlines">
                    <i class="fas fa-stopwatch"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom" title="" data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   data-bs-original-title="Finished">
                    <i class="fas fa-tasks"></i>
                </a>
            </li>
        </ul>
    </div>
    {{content}}
</div>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>
</html>