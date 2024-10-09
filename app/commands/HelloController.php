<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\IssueData;
use yii\base\Security;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $sec = new Security();
        for ($i = 0; $i < 50000; $i++) {
            $model = new IssueData();

            $model->issue_id = 33;
            $model->project_id = 43;
            $model->issue_subject = $sec->generateRandomString();
            $model->user_id = random_int(200,207);
            $model->work_type_id = random_int(1,6);

            $model->save();
        }

        return ExitCode::OK;
    }
}
