<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\IssuePriority as BaseIssuePriority;

class IssuePriority extends BaseIssuePriority
{
    use GetAllTrait;
}
