<?php

namespace app\models;

use app\core\Application;
use app\core\database\QueryBuilder;
use app\core\Model;

class ProjectModel extends Model
{
    public string $pj_name = '';
    public string $create_by = '';
    public string $pj_start = '';
    public string $pj_end_date = '';
    public string $pj_end_time = '';
    public string $pj_des = '';
    public string $userId;

    public function __construct()
    {
        $this->pj_start = date ('Y-m-d');
        $this->userId = Application::$app->user->id;
    }

    public function rules(): array
    {
        return [
            'pj_name' => [self::RULE_REQUIRED],
            'pj_start' => [self::RULE_REQUIRED],
            'pj_end_date' => [self::RULE_REQUIRED],
            'pj_end_time' => [self::RULE_REQUIRED],
            'pj_des' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'pj_name' => 'Project Name',
            'pj_start' => 'Project Start At',
            'pj_end_date' => 'Project End At Date',
            'pj_end_time' => 'Project End At Time',
            'pj_des' => 'Descriptions',
            'create_by' => 'Project Create By'
        ];
    }

    public function save()
    {
        return QueryBuilder::table ('projects')
            ->insert ([
                'pj_name' => $this->pj_name,
                'create_by' => $this->userId,
                'pj_start' => $this->pj_start,
                'pj_end_date' => $this->pj_end_date,
                'pj_end_time' => $this->pj_end_time,
                'pj_des' => $this->pj_des
            ]);
    }

    public function setLeader()
    {
        $lastRow = QueryBuilder::table ('projects')
            ->select ('id_project')
            ->orderBy ('id_project', 'desc')
            ->first ();
        return QueryBuilder::table ('project_leader')
            ->insert ([
                'pj_id' => $lastRow['id_project'],
                'user_id' => $this->userId
            ]);
    }

    public function getUserListProject()
    {
        return QueryBuilder::table ('projects')
            ->where ('create_by', '=', $this->userId)
            ->get ();
    }

    public function getJoinedProject()
    {
        return QueryBuilder::table ('project_members')
            ->join ('projects', 'projects.id_project', '=', 'project_members.pj_id')
            ->where ('users_id', '=', $this->userId)
            ->get ();
    }

    public function getProjectInfo($id)
    {
        return QueryBuilder::table ('projects')
            ->join ('users', 'users.id', '=', 'projects.create_by')
            ->where ('id_project', '=', $id)
            ->select ('projects.*', 'users.avatar')
            ->first ();
    }

    public function getMembersOfProject($id): array
    {
        return QueryBuilder::table ('project_members')
            ->join ('users', 'users.id', '=', 'project_members.users_id')
            ->where ('pj_id', '=', $id)
            ->select ('users.*')
            ->get ();
    }

    public function getLeadersOfProject($id)
    {
        return QueryBuilder::table ('project_leader')
            ->join ('users', 'users.id', '=', 'project_leader.user_id')
            ->where ('pj_id', '=', $id)
            ->select ('users.*')
            ->get ();
    }

    public function getInputUserInfo($user)
    {
        return QueryBuilder::table ('users')
            ->where ('email', '=', $user)
            ->first ();
    }

    public function checkUniqueUserInProject($idProject, $idUser): bool
    {
        $checkUserUnique = QueryBuilder::table ('project_members')
            ->where ('pj_id', '=', $idProject)
            ->where ('users_id', '=', $idUser)
            ->first ();
        $checkLeader = QueryBuilder::table ('project_leader')
            ->where ('pj_id', '=', $idProject)
            ->where ('user_id', '=', $idUser)
            ->first ();
        //insert or not
        if ($checkUserUnique || $checkLeader) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUniqueLeader($idProject, $idUser): bool
    {
        $query = QueryBuilder::table ('project_leader')
            ->where ('pj_id', '=', $idProject)
            ->where ('user_id', '=', $idUser)
            ->first ();
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function addUserToProject($idProject, $idUser)
    {
        return QueryBuilder::table ('project_members')
            ->insert ([
                'pj_id' => $idProject,
                'users_id' => $idUser
            ]);
    }

    public function setLeaderOfProject($idProject, $idUser)
    {
        //move from member to mentor
        QueryBuilder::table ('project_members')
            ->where ('pj_id', '=', $idProject)
            ->where ('users_id', '=', $idUser)
            ->delete ();
        return QueryBuilder::table ('project_leader')
            ->insert ([
                'pj_id' => $idProject,
                'user_id' => $idUser
            ]);
    }

    public function checkAccessOnProject($pj_id)
    {
        $access = QueryBuilder::table ('project_leader')
            ->where ('pj_id', '=', $pj_id)
            ->where ('user_id', '=', Application::$app->user->id)
            ->first ();
        if ($access) {
            return Application::$app->access ('MENTOR');
        } else {
            return Application::$app->access ('MEMBER');
        }
    }

}