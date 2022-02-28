<?php
/** @var $this View */

/** @var $info */

use app\core\View;

$this->title = 'Profile';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/site/profile.css">
    <script src="/js/profile.js"></script>
    <title>Document</title>
</head>
<body>
<hr class="mt-0 mb-4">
<div class="row">
    <div class="col-xl-4">
        <!-- Profile picture card-->
        <div class="card mb-4 mb-xl-0">
            <div class="card-header">Profile Picture</div>
            <div class="card-body text-center">
                <!-- Profile picture image-->
                <div class="image-upload img-account-profile rounded-circle mb-2">
                    <label for="file-input">
                        <img src="/img/users/<?= $info->avatar ?>"/>
                    </label>
                    <input id="file-input" type="file"/>
                </div>
                <!-- Profile picture help block-->
                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <!-- Account details card-->
        <div class="card mb-4">
            <div class="card-header">Account Details</div>
            <div class="card-body">
                <form>
                    <!-- Form Row        -->
                    <div class="row gx-3 mb-3">
                        <!-- Form Group (organization name)-->
                        <div class="col-md-6">
                            <label class="small mb-1" for="inputUsername">Username (how your name will appear to other
                                users on the site)</label>
                            <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username"
                                   value="<?= $info->name ?>">
                        </div>
                        <!-- Form Group (location)-->
                        <div class="col-md-6">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control" id="inputEmailAddress" type="email"
                                   placeholder="Enter your email address" value="name@example.com">
                        </div>
                    </div>
                    <!-- Form Row-->
                    <div class="mb-3">
                        <label class="small mb-1" for="inputUsername">Comfirm password</label>
                        <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username"
                               value="username">
                    </div>
                    <!-- Save changes button-->
                    <button class="btn btn-primary" type="button">Save changes</button>
                </form>
            </div>
        </div>
        <!-- Account details card-->
        <div class="card mb-4">
            <div class="card-header">Security</div>
            <div class="card-body">
                <form>
                    <!-- Form Row-->
                    <div class="mb-3">
                        <label class="small mb-1" for="inputUsername">Comfirm password</label>
                        <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username"
                               value="username">
                    </div>
                    <!-- Form Row-->
                    <div class="mb-3">
                        <label class="small mb-1" for="inputUsername">Comfirm password</label>
                        <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username"
                               value="username">
                    </div>
                    <!-- Save changes button-->
                    <button class="btn btn-primary" type="button">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

