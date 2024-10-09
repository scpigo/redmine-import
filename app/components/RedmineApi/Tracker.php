<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Tracker as BaseTracker;

class Tracker extends BaseTracker
{
    use GetAllTrait;
}
