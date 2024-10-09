<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Version as BaseVersion;

class Version extends BaseVersion
{
    use GetAllTrait;
}
