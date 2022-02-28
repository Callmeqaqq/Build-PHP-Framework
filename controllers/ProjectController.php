<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\database\QueryBuilder;
use app\core\Request;
use app\models\AutoCompleteModel;
use app\models\ProjectModel;

class ProjectController extends Controller
{
    public ProjectModel $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }



    public function createProject(Request $request)
    {
        if ($request->isPost ()) {
            $this->projectModel->loadData ($request->getBody ());
            if ($this->projectModel->validate () && $this->projectModel->save ()) {
                $this->projectModel->setLeader ();
                Application::$app->session->setFlash ('success', 'Project created successfully');
                Application::$app->response->redirect ('/list-project');
                exit;
            }
        }
        return $this->render ('create_project', [
            'model' => $this->projectModel
        ]);
    }

    public function listProject(Request $request)
    {
        $listProject = $this->projectModel->getUserListProject ();
        $listJoinedProject = $this->projectModel->getJoinedProject ();
        return $this->render ('list_projects', [
            'create' => $listProject,
            'joined' => $listJoinedProject
        ]);
    }

    public function project(Request $request)
    {
        //click add member
        if ($request->isPost ()) {
            $this->projectModel->checkAccessOnProject ($_POST['idProject']);
            $user = $_POST['searchValue'];//input value
            $userInfo = $this->projectModel->getInputUserInfo ($user);
            $checkUserUnique = $this->projectModel->checkUniqueUserInProject ($_POST['idProject'], $userInfo['id']);
            if (!$checkUserUnique) {
                return $this->projectModel->addUserToProject ($_POST['idProject'], $userInfo['id']);
            } else {
                return false;
            }
        } else {
            $parameter = $request->urlQuery ();
            $this->projectModel->checkAccessOnProject ($parameter['id']);
            $members = $this->projectModel->getMembersOfProject ($parameter['id']);
            $leaders = $this->projectModel->getLeadersOfProject ($parameter['id']);
            $project = $this->projectModel->getProjectInfo ($parameter['id']);
            return $this->render ('project_info', [
                'params' => $parameter,
                'project' => $project,
                'members' => $members,
                'leaders' => $leaders
            ]);
        }
    }
}