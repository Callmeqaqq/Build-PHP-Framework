<?php
/** @var $this \app\core\View  title */
/** @var $model \app\models\ContactForm  title */
/** @var $param \app\controllers\SiteController  title */

use app\core\form\TextareaField;

$this->title = 'Contact'
?>

<h1>Contact us</h1>
<?php $form = \app\core\form\Form::begin ('', 'post') ?>
<?= $form->input ($model, 'subject') ?>
<?= $form->input ($model, 'email') ?>
<?= $form->textarea ($model, 'body') ?>
<?= $form->select($model, 'age', $param)?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php $form = \app\core\form\Form::end () ?>
