<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\TimeEntry as BaseTimeEntry;

class TimeEntry extends BaseTimeEntry
{
    use GetAllTrait;
}
