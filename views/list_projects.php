<?php
/** @var $create */
/** @var $joined */

use app\core\Application;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        ::-webkit-scrollbar {
            width: 3px;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px silver;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(#026AA7, #6bbeef);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #83cffa;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-6">
        <h6 class="border-bottom pb-2 mb-0">Your Projects</h6>
        <div class="overflow-auto" style="width: 100%; height: 70vh;">
            <?php foreach ($create as $param): ?>
                <div class="d-flex text-muted pt-3">
                    <img class="bd-placeholder-img flex-shrink-0 me-2 rounded" style="height: 32px; width: 32px"
                         src="/img/site/new_project_header.png" alt="">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="lh-lg text-gray-dark">@<?= $param['pj_name'] ?></strong>
                            <a style="margin-right: 10px" class="btn-outline-info btn btn-sm" href="/project?id=<?= $param['id_project'] ?>">View</a>
                        </div>
                        <span class="d-block">status</span>
                        <span><?= $param['pj_des'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-6">
        <h6 class="border-bottom pb-2 mb-0">Joined Project</h6>
        <div class="overflow-auto" style="width: 100%; height: 70vh;">
            <?php foreach ($joined as $param): ?>
                <div class="d-flex text-muted pt-3">
                    <img class="bd-placeholder-img flex-shrink-0 me-2 rounded" style="height: 32px; width: 32px"
                         src="/img/site/new_project_header.png" alt="">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="lh-lg text-gray-dark">@<?= $param['pj_name'] ?></strong>
                            <a class="btn-outline-info btn btn-sm" href="/project?id=<?= $param['id_project'] ?>">View</a>
                        </div>
                        <span class="d-block">status</span>
                        <span><?= $param['pj_des'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>