<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Group as BaseGroup;

class Group extends BaseGroup
{
    use GetAllTrait;
}
