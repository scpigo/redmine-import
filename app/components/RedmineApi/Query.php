<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Query as BaseQuery;

class Query extends BaseQuery
{
    use GetAllTrait;
}
