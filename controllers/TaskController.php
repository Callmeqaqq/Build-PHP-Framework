<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\database\QueryBuilder;
use app\core\Request;
use app\models\TaskModal;
use JetBrains\PhpStorm\Pure;

class TaskController extends Controller
{
    public TaskModal $taskModal;
    const TODO = 1;
    const WORKING = 2;
    const TEST = 3;
    const ERROR = 4;
    const DONE = 5;

    #[Pure] public function __construct()
    {
        $this->taskModal = new TaskModal();
    }

    public function getTaskInProject(Request $request): bool|string
    {
        $id = $request->getRouteParams ()['id'];
        $task = [];
        $task_info = $this->taskModal->getAllTaskInProject ($id);
//        $a = Application::$app->access;
//        $task['access'][] = ['key' => Application::$app->access];
        //$task_info[0] = task, $task_info[1] = comment
        if ($task_info) {
            foreach ($task_info as $status) {
                if ($status['task_status'] == self::TODO) {//if status = to-do
                    $task['todo'][] = [
                        'id' => $status['id'],
                        'title' => $status['task_name'],
                        'des' => $status['task_des'],
                        'create_by' => $status['create_by'],
                        'create_at' => $status['create_at'],
                        'status' => $status['task_status'],
                        'deadline' => $status['task_end_at'],
                        'doing_by' => json_decode ($status['task_doing_by'], true),
                        'rating' => $status['rating'],
                    ];
                }
                if ($status['task_status'] == self::WORKING) {//if status = working
                    $task['working'][] = [
                        'id' => $status['id'],
                        'title' => $status['task_name'],
                        'des' => $status['task_des'],
                        'create_by' => $status['create_by'],
                        'create_at' => $status['create_at'],
                        'status' => $status['task_status'],
                        'deadline' => $status['task_end_at'],
                        'doing_by' => json_decode ($status['task_doing_by'], true),
                        'rating' => $status['rating']
                    ];
                }
                if ($status['task_status'] == self::TEST) {//if status = test
                    $task['test'][] = [
                        'id' => $status['id'],
                        'title' => $status['task_name'],
                        'des' => $status['task_des'],
                        'create_by' => $status['create_by'],
                        'create_at' => $status['create_at'],
                        'status' => $status['task_status'],
                        'deadline' => $status['task_end_at'],
                        'doing_by' => json_decode ($status['task_doing_by'], true),
                        'rating' => $status['rating']
                    ];
                }
                if ($status['task_status'] == self::ERROR) {//if status = error
                    $task['error'][] = [
                        'id' => $status['id'],
                        'title' => $status['task_name'],
                        'des' => $status['task_des'],
                        'create_by' => $status['create_by'],
                        'create_at' => $status['create_at'],
                        'status' => $status['task_status'],
                        'deadline' => $status['task_end_at'],
                        'doing_by' => json_decode ($status['task_doing_by'], true),
                        'rating' => $status['rating']
                    ];
                }
                if ($status['task_status'] == self::DONE) {//if status = done
                    $task['done'][] = [
                        'id' => $status['id'],
                        'title' => $status['task_name'],
                        'des' => $status['task_des'],
                        'create_by' => $status['create_by'],
                        'create_at' => $status['create_at'],
                        'deadline' => $status['task_end_at'],
                        'status' => $status['task_status'],
                        'doing_by' => json_decode ($status['task_doing_by'], true),
                        'rating' => $status['rating']
                    ];
                }
            }
        }
        return json_encode ($task);
    }

    public function createNewTask()
    {
        return $this->taskModal->createNewTaskInProject ();
    }

    public function updateStatus(): bool
    {
        return $this->taskModal->updateStatusOfTaskInProject ($_POST['idTask'], $_POST['statusId']);
    }

    public function getCommentOfTask(Request $request)
    {
        $taskId = $request->getRouteParams ()['id'];
        return $this->taskModal->getComments($taskId);
    }

    public function checkUniqueUserInTask()
    {
        $email = $_POST['email'];
        $pj_id = $_POST['projectId'];
        return $this->taskModal->addUserIfValid($email, $pj_id);
    }

    public function addCommentToTask()
    {
        $comment = $_POST['comment'];
        $id = $_POST['id'];
        $userId = Application::$app->user->id;
        return $this->taskModal->addCommentToTask($comment, $id, $userId);
    }

    public function updateTaskInfo()
    {
        $task_name = $_POST['task_name'];
        $task_des = $_POST['task_des'];
        $rating = $_POST['rating'];
        $date = $_POST['date'];
        $id = $_POST['id'];
        return $this->taskModal->updateTaskInfo($task_name,$task_des,$rating,$date, $id);
    }

    public function getTaskStatistical(Request $request)
    {
        $pj_id = $request->getRouteParams ()['id'];
        return $this->taskModal->getStatistical($pj_id);
    }
}