<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\User as BaseUser;

class User extends BaseUser
{
    use GetAllTrait;
}
