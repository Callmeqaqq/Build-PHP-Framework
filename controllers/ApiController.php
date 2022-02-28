<?php

namespace app\controllers;

use app\core\Controller;
use app\core\database\QueryBuilder;
use app\core\Request;
use app\models\ProjectModel;

class ApiController extends Controller
{

    public function getListUser()
    {
        $render = '';
        if (isset($_POST['searchValue'])) {
            $results = QueryBuilder::table ('users')
                ->where ('email', 'like', ($_POST['searchValue']))
                ->orWhere ('name', 'like', $_POST['searchValue'])
                ->limit (2)
                ->get ();
            if ($results) {
                foreach ($results as $result) {
                    $render .=
                        "<li>
                            <span>" . $result['email'] . "</span>
                        </li>";
                }
                return $render;
            } else {
                $render .= "<span>
                            <p style='color:red;margin: 0'>Not Found!</input>
                        </span>";
            }
        }
        return $render;
    }

    public function getValidListUser()
    {
        $render = '';
        if (isset($_POST['searchValue'])) {
            $userId = QueryBuilder::table ('users')
                ->select ('id')
                ->where ('email', 'like', $_POST['searchValue'])
                ->first ();
            if ($userId) {
                $results = QueryBuilder::table ('project_members')
                    ->join ('users', 'users.id', '=', $userId['id'])
                    ->where ('pj_id', '=', $_POST['idProject'])
                    ->where ('users_id', '=', $userId['id'])
                    ->limit (2)
                    ->get ();
                if ($results) {
                    foreach ($results as $result) {
                        $render .=
                            "<li>
                            <span>" . $result['email'] . "</span>
                        </li>";
                    }
                    return $render;
                } else {
                    $render .= "<span>
                            <p style='color:red;margin: 0'>Not Found!</input>
                        </span>";
                }
            }
        }
        return $render;
    }

    public function setLeaderOfProject(Request $request)
    {
        if ($request->isPost ()) {
            $projectModel = new ProjectModel();
            $userInfo = $projectModel->getInputUserInfo ($_POST['searchValue']);
            $checkUserUnique = $projectModel->checkUniqueLeader($_POST['idProject'], $userInfo['id']);
            if (!$checkUserUnique) {
                return $projectModel->setLeaderOfProject($_POST['idProject'], $userInfo['id']);
            } else {
                return false;
            }
        }
        return false;
    }
}