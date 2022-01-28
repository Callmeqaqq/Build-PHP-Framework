<?php
//declare variable $model is instance of Class User in app\models\user

/** @var $model User */

use app\core\form\Form;
use app\models\User;

?>


<?php
/**@var $model \app\models\User */
?>
<!doctype html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/auth/login.css">
</head>
<body>
<section class="">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url('img/auth/bg-2.jpg');">
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Register</h3>
                            </div>
                            <div class="w-100">
                                <p class="social-media d-flex justify-content-end">
                                    <a href="#"
                                       class="social-icon d-flex align-items-center justify-content-center"><span
                                                class="fa fa-facebook"></span></a>
                                    <a href="#"
                                       class="social-icon d-flex align-items-center justify-content-center"><span
                                                class="fa fa-twitter"></span></a>
                                </p>
                            </div>
                        </div>
                        <?php $form = Form::begin ('', 'post') ?>
                        <?php echo $form->input ($model, 'name') ?>
                        <?php echo $form->input ($model, 'email') ?>
                        <div class="row">
                            <div class="col"><?php echo $form->input ($model, 'password')->passwordField () ?></div>
                            <div class="col"><?php echo $form->input ($model, 'confirmPassword')->passwordField () ?></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary rounded submit px-3">
                                Sign-up
                            </button>
                        </div>
                        <?php Form::end () ?>
                        <a class="text-center">Already have an account? <a href="/login">Sign In</a></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>

</html>
