<?php
namespace app\commands;

use app\components\RedmineClient;
use yii\base\Security;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class RedmineController extends Controller
{
    public function actionExportData($projectId = 84)
    {
        /** @var RedmineClient $redmine_old */
        $redmine_old = \Yii::$app->redmine_old;

        echo "exporting users...\n";

        $resultUsers = [];

        $users = $redmine_old->getAllUsers();
        foreach ($users as $userData) {
            $resultUsers[$userData['id']] = $userData;
        }

        file_put_contents(Yii::getAlias('@app').'/redmine_data/users.json', json_encode($resultUsers));

        echo "exporting memberships...\n";

        $resultMemberships = [];

        $memberships = $redmine_old->getAllMemberships($projectId);
        foreach ($memberships as $membershipData) {
            $redmineUser = $redmine_old->getUserById($membershipData['user']['id']);

            $resultMemberships[$redmineUser['id']] = $redmineUser;
        }

        file_put_contents(Yii::getAlias('@app').'/redmine_data/memberships.json', json_encode($resultMemberships));

        echo "exporting issues...\n";

        $resultIssues = $redmine_old->getAllIssuesParams([
            'project_id' => $projectId,
            'limit' => 15000,
            'status_id' => '*'
        ]);

        foreach ($resultIssues as $key => $issue) {
            $resultIssues[$key] = $redmine_old->getIssue($issue['id'], [
                'include' => 'attachments,journals'
            ]);
        }

        file_put_contents(Yii::getAlias('@app').'/redmine_data/issues.json', json_encode($resultIssues));

        echo "exporting time entries...\n";

        $resultTimeEntries = $redmine_old->getAllTimeEntriesParams([
            'project_id' => $projectId,
            'limit' => 30000
        ]);

        file_put_contents(Yii::getAlias('@app').'/redmine_data/timeentries.json', json_encode($resultTimeEntries));
    }

    public function actionImportData(bool $newUsers = true, $projectId = 1, $oldProjectId = 84)
    {
        /** @var RedmineClient $redmine_new */
        $redmine_old = \Yii::$app->redmine_old;

        /** @var RedmineClient $redmine_new */
        $redmine_new = \Yii::$app->redmine_new;

        $usersData = json_decode(file_get_contents(Yii::getAlias('@app').'/redmine_data/users.json'), true);
        $membershipsData = json_decode(file_get_contents(Yii::getAlias('@app').'/redmine_data/memberships.json'), true);
        $issuesData = json_decode(file_get_contents(Yii::getAlias('@app').'/redmine_data/issues.json'), true);
        $timeEntriesData = json_decode(file_get_contents(Yii::getAlias('@app').'/redmine_data/timeentries.json'), true);

        $priorities = $redmine_new->getAllPriorities(['offset' => 0, 'limit' => 100]);
        $activities = $redmine_new->getAllTimeActivity(['offset' => 0, 'limit' => 100]);
        $managerRole = 0;
        foreach ($redmine_new->getRoles() as $role) {
            if ($role['name'] === 'Менеджер') {
                $managerRole = $role['id'];
                break;
            }
        }

        $users = [];
        $usedUsersNames = [];

        if ($newUsers) {
            echo "importing users...\n";

            foreach ($usersData as $userData) {
                $createUserData = [
                    'login' => $userData['login'],
                    'password' => \Yii::$app->security->generateRandomString(12),
                    'lastname' => $userData['lastname'],
                    'firstname' => $userData['firstname'],
                    'mail' => $userData['mail'],
                ];

                $result = (array)$redmine_new->createUser($createUserData);

                if ($result && $id = (array)$result[0]) {
                    $users[$userData['lastname'] .' '. $userData['firstname']] = array_merge($userData, ['id' => $id[0]]);

                    $redmine_new->createMembership($projectId, [
                        'user_id' => $id[0],
                        'role_ids' => [$managerRole]
                    ]);
                }
            }
        } else {
            $usersData = $redmine_new->getAllUsers();

            foreach ($usersData as $userData) {
                $users[$userData['lastname'] .' '. $userData['firstname']] = $userData;

                $redmine_new->createMembership($projectId, [
                    'user_id' => $userData['id'],
                    'role_ids' => [$managerRole]
                ]);
            }
        }

        echo "importing issues...\n";

        $issues = [];
        $issuesExtra = [];

        foreach ($issuesData as $issueData) {
            $login = null;

            $createIssueData = [
                'project_id' => $projectId,
                'tracker' => $issueData['tracker']['name'],
                'status' => $issueData['status']['name'],
                'priority_id' => $priorities[$issueData['priority']['name']]['id'],
                'subject' => $issueData['subject'],
                'description' => $issueData['description'],
                'due_date' => $issueData['due_date'],
                'start_date' => $issueData['start_date'],
                'estimated_hours' => $issueData['estimated_hours'],
                'custom_fields' => $issueData['custom_fields']
            ];

            if (isset($issueData['assigned_to']) && isset($users[$issueData['assigned_to']['name']])) {
                $createIssueData['assigned_to'] = $issueData['assigned_to']['name'];

                $login = $users[$issueData['assigned_to']['name']]['login'];
                $usedUsersNames = self::setUsedUser($usedUsersNames, $issueData['assigned_to']['name']);
            }

            if (isset($issueData['category'])) $createIssueData['category'] = $issueData['category']['name'];

            if (isset($issueData['attachments']) && !empty($issueData['attachments'])) {
                $attachmentsNew = [];
                foreach ($issueData['attachments'] as $attachment) {
                    if (isset($users[$attachment['author']['name']])) {
                        $attachmentsNew[$attachment['id']] = [
                            'id' => $attachment['id'],
                            'filename' => $attachment['filename'],
                            'content_type' => $attachment['content_type'],
                        ];
                    }
                }

                if (!empty($attachmentsNew)) {
                    unset($issueData['attachments']);
                    $issueData['attachments'] = $attachmentsNew;
                }
            }

            $result = $redmine_new->createTask($createIssueData, $login);

            if ($result && $id = (array)$result[0]) {
                $issues[$issueData['id']] = array_merge($issueData, ['id' => $id[0]]);

                if (isset($issueData['parent']) && isset($issues[$issueData['parent']['id']])) {
                    $issuesExtra[$issueData['id']] = array_merge($issueData, ['id' => $id[0]]);
                }

                if (isset($issueData['journals']) && !empty($issueData['journals'])) {
                    foreach ($issueData['journals'] as $journal) {
                        $journalLogin = $users[$journal['user']['name']]['login'];

                        $journalData = [];

                        $uploads = [];
                        foreach ($journal['details'] as $detail) {
                            if ($detail['property'] === 'attachment') {
                                $attachment = $issueData['attachments'][$detail['name']];
                                $uploads[] = [
                                    'token' => $redmine_new->createAttachment($redmine_old->downloadAttachment($attachment['id']), ['filename' => $attachment['filename']], $journalLogin),
                                    'filename' => $attachment['filename'],
                                    'content_type' => $attachment['content_type'],
                                ];
                            }
                        }
                        if (!empty($uploads)) {
                            $journalData['uploads'] = $uploads;
                        }

                        if ($journal['notes'] != '') {
                            if (isset($users[$journal['user']['name']])) {
                                $journalData['notes'] = $journal['notes'];
                            }
                        }

                        if (!empty($journalData)) {
                            $redmine_new->updateIssue($id[0], $journalData, $journalLogin);
                            $usedUsersNames = self::setUsedUser($usedUsersNames, $journal['user']['name']);
                        }
                    }
                }
            }
        }

        echo "importing parent issues...\n";

        foreach ($issuesExtra as $issueData) {
            $updateIssueData = [
                'parent_issue_id' => $issues[$issueData['parent']['id']]['id']
            ];

            $redmine_new->updateIssue($issueData['id'], $updateIssueData);
        }

        echo "importing time entries...\n";

        foreach ($timeEntriesData as $timeEntryData) {
            if (isset($issues[$timeEntryData['issue']['id']]) && isset($users[$timeEntryData['user']['name']])) {
                $createTimeEntryData = [
                    'issue_id' => $issues[$timeEntryData['issue']['id']]['id'],
                    'user_id' => $users[$timeEntryData['user']['name']]['id'],
                    'project_id' => $projectId,
                    'spent_on' => $timeEntryData['spent_on'],
                    'hours' => $timeEntryData['hours'],
                    'activity_id' => $activities[$timeEntryData['activity']['name']]['id'],
                    'comments' => $timeEntryData['comments'],
                ];

                $redmine_new->createTimeEntry($createTimeEntryData, $users[$timeEntryData['user']['name']]['login']);

                $usedUsersNames = self::setUsedUser($usedUsersNames, $timeEntryData['user']['name']);
            }
        }

        echo "importing memberships...\n";

        $redmine_new->deleteAllMemberships($projectId);

        foreach ($membershipsData as $userData) {
            $roleIds = [];

            foreach ($userData['memberships'] as $membership) {
                if ($membership['project']['id'] == $oldProjectId) {
                    foreach ($membership['roles'] as $role) {
                        $roleIds[] = $role['id'];
                    }

                    break;
                }
            }

            $redmine_new->createMembership($projectId, [
                'user_id' => $users[$userData['lastname'] .' '. $userData['firstname']]['id'],
                'role_ids' => $roleIds
            ]);

            $usedUsersNames = self::setUsedUser($usedUsersNames, $userData['lastname'] .' '. $userData['firstname']);
        }

        if ($newUsers) {
            echo "removing unnecessary users...\n";

            foreach ($users as $name => $user) {
                if (!in_array($name, $usedUsersNames)) {
                    $redmine_new->deleteUser($user['id']);
                }
            }
        }
    }

    protected function setUsedUser(array $users, string $name)
    {
        if (!in_array($name, $users)) {
            $users[] = $name;
        }

        return $users;
    }
}
