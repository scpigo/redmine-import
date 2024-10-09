<?php

$classes = [
    'attachment'          => 'Attachment',
    'group'               => 'Group',
    'custom_fields'       => 'CustomField',
    'issue'               => 'Issue',
    'issue_category'      => 'IssueCategory',
    'issue_priority'      => 'IssuePriority',
    'issue_relation'      => 'IssueRelation',
    'issue_status'        => 'IssueStatus',
    'membership'          => 'Membership',
    'news'                => 'News',
    'project'             => 'Project',
    'query'               => 'Query',
    'role'                => 'Role',
    'time_entry'          => 'TimeEntry',
    'time_entry_activity' => 'TimeEntryActivity',
    'tracker'             => 'Tracker',
    'user'                => 'User',
    'version'             => 'Version',
    'wiki'                => 'Wiki',
];

foreach ($classes as $class) {
    $php =
<<<php
<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\\{$class} as Base{$class};

class {$class} extends Base{$class}
{
    use GetAllTrait;
}
php;

    $path = __DIR__ . '/' . $class . '.php';

    if (file_exists($path)) {
        unlink($path);
    }
    file_put_contents($path, $php);
}
