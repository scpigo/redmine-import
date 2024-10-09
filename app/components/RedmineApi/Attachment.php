<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Attachment as BaseAttachment;

class Attachment extends BaseAttachment
{
    use GetAllTrait;
}
