<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Membership as BaseMembership;

class Membership extends BaseMembership
{
    use GetAllTrait;
}
