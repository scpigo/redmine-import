<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\IssueCategory as BaseIssueCategory;

class IssueCategory extends BaseIssueCategory
{
    use GetAllTrait;
}
