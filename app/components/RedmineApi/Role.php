<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Role as BaseRole;

class Role extends BaseRole
{
    use GetAllTrait;
}
