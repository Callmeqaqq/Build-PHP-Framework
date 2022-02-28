<?php
/** @var $params ProjectModel */
/** @var $project ProjectModel */
/** @var $members ProjectModel */
/** @var $leaders ProjectModel */

/** @var $this View */

use app\core\Application;
use app\core\View;
use app\models\ProjectModel;

$this->title = $project['pj_name'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/site/pj_info.css">
    <link rel="stylesheet" href="css/site/jkanban.min.css"/>
    <script src="/js/auto_complete.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
    <style>
        .swal2-html-container {
            text-align: left;
        }

        textarea {
            width: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
YOU'RE <span id="access"><?= Application::$app->access?></span>

<div class="row">
    <div class="py-5 text-center">
        <img style="width: 100px" src="/img/users/<?= $project['avatar'] ?>" alt="Creator">
        <h2><?= $project['pj_name'] ?></h2>
        <p class="lead"><?= $project['pj_des'] ?></p>
    </div>
    <div class="d-flex align-items-center member">
        <span>Mentors: </span>
        <ul class="avatars">
            <?php foreach ($leaders as $leader): ?>
                <li>
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $leader['email'] ?>"
                       data-bs-original-title="<?= $leader['email'] ?>">
                        <img alt="Claire Connors" class="avatar" src="img/users/<?= Application::$app->user->avatar ?>">
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        if (Application::$app->access === 'MENTOR'): ?>
            <button class="add btn btn-outline-info flex-shrink-0" data-bs-toggle="modal"
                    data-bs-target="#exampleModalLeader">
                <i class="fas fa-plus"></i>
            </button>
            <!-- Button trigger modal -->
            <!-- Modal of ad Add Leader -->
            <div class="modal fade" id="exampleModalLeader" tabindex="-1" aria-labelledby="exampleModalLabelLeader"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelLeader">Set Leader</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input id="search-userLeader" class="form-control me-2" type="search" placeholder="Search"
                                   aria-label="Search" name="search">
                            <ul class="dropdown dropdown" id="dropdown-2">
                                <li>
                                </li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="add-userLeader" type="button" class="btn btn-primary">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--        success modal-->
            <div class="modal fade" id="exampleModalToggle2Leader" aria-hidden="true"
                 aria-labelledby="exampleModalToggleLabel2Leader"
                 tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel2Leader">Success!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            That user now is a mentor!
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary button-successLeader" data-bs-target="#exampleModalToggle"
                                    data-bs-toggle="modal">
                                Great
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--        fail modal-->
            <div class="modal fade" id="exampleModalToggle3Leader" aria-hidden="true"
                 aria-labelledby="exampleModalToggleLabel3Leader"
                 tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel3Leader">Fail!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Can not find user in project/User is a mentor before!
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary button-failLeader" data-bs-target="#exampleModalToggle"
                                    data-bs-toggle="modal">Try
                                again!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="d-flex align-items-center member">
        <span>Member: </span>
        <ul class="avatars">
            <?php foreach ($members as $member): ?>
                <li>
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $member['email'] ?>"
                       data-bs-original-title="<?= $member['email'] ?>">
                        <img alt="Claire Connors" class="avatar" src="img/users/<?= Application::$app->user->avatar ?>">
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <button class="add btn btn-outline-info flex-shrink-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-plus"></i>
        </button>
        <!-- Button trigger modal -->

        <!-- Modal of add Member -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input id="search-user" class="form-control me-2" type="search" placeholder="Search"
                               aria-label="Search" name="search">
                        <ul class="dropdown" id="dropdown">
                            <li>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="add-user" type="button" class="btn btn-primary">Add User</button>
                    </div>
                </div>
            </div>
        </div>
        <!--        success modal-->
        <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel2">Success!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        The user has been added to the project!
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary button-success" data-bs-target="#exampleModalToggle"
                                data-bs-toggle="modal">
                            Great
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--        fail modal-->
        <div class="modal fade" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel2">Fail!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Can not find user!/User is available!
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary button-fail" data-bs-target="#exampleModalToggle"
                                data-bs-toggle="modal">Try
                            again!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if (Application::$app->access === 'MENTOR'): ?>
        <div>
            <button class="btn btn-outline-info" id="addToDo">Add new To Do task</button>
            <button class="btn btn-outline-warning" id="showStatistical">Statistical</button>
        </div>
        <?php endif;?>
        <div style="margin-bottom: 10px" id="myKanban"></div>

    </div>
    <div id="task-infomation">

    </div>

    <script src="/js/drag_and_drop.js"></script>
    <script src="/js/jkanban.min.js"></script>
</div>
</body>
</html>