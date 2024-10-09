<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Issue as BaseIssue;

class Issue extends BaseIssue
{
    use GetAllTrait;
}
