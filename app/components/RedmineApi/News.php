<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\News as BaseNews;

class News extends BaseNews
{
    use GetAllTrait;
}
