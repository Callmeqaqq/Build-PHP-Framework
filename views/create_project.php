<?php
/** @var $model ProjectModel */

/** @var $this View  title */

use app\core\form\Form;
use app\core\View;
use app\models\ProjectModel;

$this->title = 'Create new project';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<main style="margin-bottom: 30px">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="/img/site/new_project_header.png" alt="" width="72" height="57">
        <h2>Create New Project</h2>
        <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group
            has a validation state that can be triggered by attempting to submit the form without completing it.</p>
    </div>
    <?php $form = Form::begin ('', 'post') ?>
    <?= $form->input ($model, 'pj_name') ?>
    <?= $form->textarea ($model, 'pj_des') ?>
    <div class="row">
        <div class="col"><?= $form->input ($model, 'create_by', 'disabled') ?></div>
        <div class="col"><?= $form->input ($model, 'pj_start')->dateField () ?></div>
    </div>
    <div class="row">
        <div class="col"><?= $form->input ($model, 'pj_end_date')->dateField () ?></div>
        <div class="col"><?= $form->input ($model, 'pj_end_time')->timeField () ?></div>
    </div>
    <div class="form-group">
        <button type="submit" class="form-control btn btn-primary rounded submit px-3">
            Create
        </button>
    </div>
    <?php Form::end () ?>
</main>
</body>
</html>