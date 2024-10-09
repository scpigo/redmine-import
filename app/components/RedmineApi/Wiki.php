<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Wiki as BaseWiki;

class Wiki extends BaseWiki
{
    use GetAllTrait;
}
