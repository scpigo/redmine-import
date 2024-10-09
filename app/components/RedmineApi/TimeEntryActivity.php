<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\TimeEntryActivity as BaseTimeEntryActivity;

class TimeEntryActivity extends BaseTimeEntryActivity
{
    use GetAllTrait;
}
