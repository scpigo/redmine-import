<?php

namespace app\controllers;

use Yii;
use app\models\IssueData;
use yii\web\Controller;

class TestController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        ini_set("memory_limit","512M");

        $start = microtime( true );

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            EXPLAIN FORMAT=TREE
            SELECT *
            FROM issue_data 
                LEFT JOIN user ON issue_data.user_id = user.id
                LEFT JOIN project ON issue_data.project_id = project.id
                LEFT JOIN work_type ON issue_data.work_type_id = work_type.id
            WHERE issue_data.issue_subject = \"изменить создание поста по шаблону\"");

        $result = $command->queryAll();

        $diff = sprintf( '%.6f sec.', microtime( true ) - $start );

        echo '<pre>';
        print_r($diff);
        echo "\n";
        print_r($result);
    }
}