<?php
    //declare variable $model is instance of Class User in app\models\user
    /** @var $model User */

use app\core\form\Form;
use app\models\User;
?>

<h1>Register</h1>
<?php $form = Form::begin ('', 'post') ?>
<?php echo $form->input ($model, 'name') ?>
<?php echo $form->input ($model, 'email') ?>

<div class="row">
    <div class="col"><?php echo $form->input ($model, 'password')->passwordField () ?></div>
    <div class="col"><?php echo $form->input ($model, 'confirmPassword')->passwordField () ?></div>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end () ?>
