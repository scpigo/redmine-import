<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\IssueRelation as BaseIssueRelation;

class IssueRelation extends BaseIssueRelation
{
    use GetAllTrait;
}
