<?php
/** @var $this \app\core\View  title */

use app\core\Application;

$this->title = 'Welcome ' . Application::$app->user->getDisplayName ();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container px-4 py-5" id="hanging-icons">
    <h2 class="pb-2 border-bottom">Tasks running out of time</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                <i class="fas fa-user-ninja"></i>
            </div>
            <div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence
                    and probably just keep going until we run out of words.</p>
                <a href="#" class="btn btn-primary">
                    Go to task
                </a>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                <i class="fas fa-user-ninja"></i>
            </div>
            <div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence
                    and probably just keep going until we run out of words.</p>
                <a href="#" class="btn btn-primary">
                    Go to task
                </a>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                <i class="fas fa-user-ninja"></i>
            </div>
            <div>
                <h2>Featured title</h2>
                <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence
                    and probably just keep going until we run out of words.</p>
                <a href="#" class="btn btn-primary">
                    Go to task
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>