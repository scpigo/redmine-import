<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\IssueStatus as BaseIssueStatus;

class IssueStatus extends BaseIssueStatus
{
    use GetAllTrait;
}
