<?php

namespace app\models;

use app\core\Application;
use app\core\database\QueryBuilder;
use app\core\Model;

class TaskModal extends Model
{

    public function rules(): array
    {
        // TODO: Implement rules() method.
    }

    public function getAllTaskInProject($id): array
    {
        return QueryBuilder::table ('project_task')
            ->where ('pj_id', '=', $id)
            ->get ();
    }

    public function createNewTaskInProject()
    {
        return QueryBuilder::table ('project_task')
            ->insert ([
                'pj_id' => $_POST['idProject'],
                'task_name' => $_POST['title'],
                'task_status' => $_POST['statusId'],
                'create_by' => Application::$app->user->id
            ]);
    }

    public function updateStatusOfTaskInProject($idTask, $idStatus)
    {
        return QueryBuilder::table ('project_task')
            ->where ('id', '=', $idTask)
            ->update ([
                'task_status' => $idStatus,
//                'task_name' => "test"
            ]);
    }

    public function getComments($id)
    {
        return json_encode (
            QueryBuilder::table ('comment')
                ->where ('task_id', '=', $id)
                ->get ()
        );
    }

    public function addUserIfValid($email, $id)
    {
        $user = QueryBuilder::table ('users')
            ->select ('id', 'name')
            ->where ('email', '=', $email)
            ->first ();
        if (!$user) {
            return false;
        }
        $a = QueryBuilder::table ('project_task')
            ->select ('task_doing_by')
            ->where ('id', '=', $id)
            ->first ();
        $a = json_decode ($a['task_doing_by'], true);
        $a += [$user['id'] => $user['name']];
        return QueryBuilder::table ('project_task')
            ->where ('id', '=', $id)
            ->update ([
                'task_doing_by' => json_encode ($a)
            ]);
    }

    public function addCommentToTask(mixed $comment, mixed $id, $userId)
    {
        return QueryBuilder::table ('comment')
            ->insert ([
                'task_id' => $id,
                'user_id' => $userId,
                'body' => $comment
            ]);
    }

    public function updateTaskInfo(mixed $task_name, mixed $task_des, mixed $rating, mixed $date, mixed $id)
    {
        return QueryBuilder::table ('project_task')
            ->where ('id', '=', $id)
            ->update ([
                'task_name' => $task_name,
                'task_des' => $task_des,
                'rating' => $rating,
                'task_end_at' => $date,
            ]);
    }

    public function getStatistical(mixed $pj_id)
    {
        return json_encode (QueryBuilder::table ('project_task')
            ->where ('pj_id', '=', $pj_id)
            ->get ());
    }
}