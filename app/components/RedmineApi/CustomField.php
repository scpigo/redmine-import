<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\CustomField as BaseCustomField;

class CustomField extends BaseCustomField
{
    use GetAllTrait;
}
