<?php

use app\core\Application;

/** @var $this \app\core\View  title */

$this->title = 'Home';

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

    <title><?= $this->title ?></title>
</head>
<body>
<!--navbar-->
<div class="container d-flex">
    <ul class="nav me-auto">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/user">user</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/contact">contact</a>
        </li>
    </ul>
    <?php if (Application::isGuest ()): ?>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/login">login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/register">register</a>
            </li>
        </ul>
    <?php else: ?>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/profile">Profile</a>
            </li>
        </ul>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/logout">Welcome <?php echo Application::$app->user->getDisplayName () ?>
                    (Logout)</a>
            </li>
        </ul>
    <?php endif; ?>
</div>

<div class="container">
    <?php
    if (Application::$app->session->getFlash ('success')):?>
        <div class="alert alert-success">
            <?= Application::$app->session->getFlash ('success') ?>
        </div>
    <?php endif; ?>
    {{content}}
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
-->
</body>
</html>