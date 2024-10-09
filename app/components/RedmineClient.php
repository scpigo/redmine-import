<?php


namespace app\components;

use app\components\RedmineApi\api\src\Redmine\Api\Issue;
use app\components\RedmineApi\api\src\Redmine\Client;
use Carbon\Carbon;

class RedmineClient extends Client
{
    /**
     * @var array APIs
     */
    private $apis = [];

    private $classes = array(
        'attachment' => 'Attachment',
        'group' => 'Group',
        'custom_fields' => 'CustomField',
        'issue' => 'Issue',
        'issue_category' => 'IssueCategory',
        'issue_priority' => 'IssuePriority',
        'issue_relation' => 'IssueRelation',
        'issue_status' => 'IssueStatus',
        'membership' => 'Membership',
        'news' => 'News',
        'project' => 'Project',
        'query' => 'Query',
        'role' => 'Role',
        'time_entry' => 'TimeEntry',
        'time_entry_activity' => 'TimeEntryActivity',
        'tracker' => 'Tracker',
        'user' => 'User',
        'version' => 'Version',
        'wiki' => 'Wiki',
    );

    /*relation id roles in redmine with crm*/
    private $roleNames = [
        3 => 'manager',
        8 => 'teamlead'
    ];

    public function getRoleIdByName($name){
        return array_search($name, $this->roleNames);
    }

    public function getAllProjects()
    {
        $response = $this->project->all();
        return ($response['projects']) ? $response['projects'] : [];
    }


    public function getAllTimeActivity($params = [])
    {
        $activities = $this->time_entry_activity->all($params)['time_entry_activities'];

        $result = [];

        foreach ($activities as $activity) {
            $result[$activity['name']] = $activity;
        }

        return $result;
    }


    public function getAllCustomFieldsByType($types) {
        $customFields = $this->getAllCustomFields();
        $return = [];
        foreach ($customFields as $customField) {
            if(in_array($customField['field_format'], $types)) {
                $return[] = $customField;
            }
        }
        return $return;
    }


    public function getAllCustomFields()
    {
        $response = $this->custom_fields->all(['field_format' => 'text']);
        return ($response['custom_fields']) ? $response['custom_fields'] : [];
    }

    /**
     * Метод возвращает формализованный массив кастомных полей из редмайна типа "list" и + time_entry_activities.
     * Нужно для настроек определения сверхурочного времени. Метка сверхурочки может оказывается хранится не толкьо в time_entry_activities
     * @param $clearCache boolean //Сбросить кеш
     * @return array
     */
    public function getCustomFieldsForTimeEntry($clearCache = false) {
        //Сначало попробуем получить из кеша
        $customFieldsForTimeEntry = \Yii::$app->cache->get('customFieldsForTimeEntry');
        if(isset($customFieldsForTimeEntry) && is_array($customFieldsForTimeEntry) && !$clearCache) {
            $return = $customFieldsForTimeEntry;
        } else {
            $activityTypes = [];
            //Сначала получим time_entry_activities
            foreach ($this->getAllTimeActivity() as $activityType) {
                $activityTypes[$activityType['id']] = $activityType['name'];
            }
            //Поле time_entry_activities не является кастомным и у него нет своего ID, так что пусть оно будет нулемы
            $return[0] = [
                'name' => 'Тип активности',
                'values' => $activityTypes
            ];

            //Теперь получим все кастомные поля, которые относятся к отметкам времени
            $customFields = $this->getAllCustomFields();
            foreach ($customFields as $customField) {
                if($customField['customized_type'] == 'time_entry' && $customField['field_format'] == 'list') {
                    $values = [];
                    foreach ($customField['possible_values'] as $key => $value) {
                        $values[$key+1] = $value['value'];
                    }
                    $return[$customField['id']] = [
                        'name' => $customField['name'],
                        'values' => $values
                    ];
                }
            }
            //Запишем в кеш, чтобы синхронизация по задачам работала быстрее
            \Yii::$app->cache->set('customFieldsForTimeEntry', $return);
        }
        return $return;
    }

    public function getAllUsers()
    {
        $response = $this->user->all();
        $active = $response['users'];
        $response = $this->user->all(['status' => 3]);
        $blocked = $response['users'];

        $result = $active;
        foreach ($blocked as $user) {
            $result[] = $user;
        }

        return $result;
    }

    public function getTimeEntriesForDate(Carbon $date)
    {
        $response = $this->time_entry->all([
            'limit' => 100,
            'offset' => 0,
            'spent_on' => $date->toDateString(),
        ]);

        $result = $response['time_entries'];
        $total = $response['total_count'];

        $offset = count($result);
        while ($offset < $total) {
            $response = $this->time_entry->all([
                'limit' => 100,
                'offset' => $offset,
                'spent_on' => $date->toDateString(),
            ]);

            $result = array_merge($result, $response['time_entries']);
            $offset = count($result);
        }

        return $result;
    }

    public function getAllTimeEntries($limit, $offset)
    {
        $response = $this->time_entry->all([
            'limit' => $limit,
            'offset' => $offset
        ]);

        return $response['time_entries'];
    }

    public function getAllTimeEntriesParams(array $params)
    {
        $response = $this->time_entry->all($params);

        return $response['time_entries'];
    }

    public function getAllIssues($limit, $offset, $statusId = null)
    {
        if ($statusId === null) {
            $response = $this->issue->all([
                'limit' => $limit,
                'offset' => $offset
            ]);
        } else {
            $response = $this->issue->all([
                'limit' => $limit,
                'offset' => $offset,
                'status_id' => $statusId,
            ]);
        }

        return $response['issues'];
    }

    public function getAllIssuesParams(array $params)
    {
        $response = $this->issue->all($params);

        return $response['issues'];
    }

    public function getTimeEntriesFrom($date)
    {
        $filter = [
            'spent_on' => '>='.$date
        ];

        $response = $this->time_entry->all($filter);

        return $response['time_entries'];
    }

    public function getUserById($id)
    {
        $response = $this->user->show($id);

        return isset($response['user']) ? $response['user'] : null;
    }

    public function getUserByEmail($email)
    {
        $response = $this->user->all();
        foreach ($response['users'] as $user) {
            if ($user['mail'] == $email) {
                return $user;
            }
        }
        return null;
    }

    public function getUserByName($name)
    {
        $response = $this->user->all();
        foreach ($response['users'] as $user) {
            if ($user['name'] == $name) {
                return $user;
            }
        }
        return null;
    }

    public function getProjectById($id)
    {
        $response = $this->project->show($id);

        return $response['project'];
    }

    public function createTask($data, $login)
    {
        $this->setImpersonateUser($login);
        $fields = [
            'project_id',
            'subject',
            'description',
            'assigned_to_id',
            'status_id',
            'tracker_id',
            'due_date',
            'start_date',
            'estimated_hours',
        ];

        $issueData = [];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $issueData[$field] = $data[$field];
            }
        }

        $response = $this->issue->create($data);

        $this->setImpersonateUser(null);

        return isset($response->id) ? $response->id : false;
    }

    public function createProject($data)
    {
        $response = $this->project->create($data);

        return isset($response->id) ? $response->id : false;
    }

    public function updateProject($id, $data)
    {
        $this->project->update($id, $data);
    }

    public function getIssue($issueId, $params)
    {
        $response = $this->issue->show($issueId, $params);

        return $response['issue'];
    }

    public function updateIssue($issueId, $params, $username = null){
        $this->setImpersonateUser($username);
        $this->issue->update($issueId, $params);
        $this->setImpersonateUser(null);
    }

    public function getRoles(){
        return $this->role->all(['offset' => 0, 'limit' => 100])['roles'];
    }

    public function deleteAllMemberships(int $projectId){
        $memberships = $this->membership->all($projectId)['memberships'];

        foreach ($memberships as $membership) {
            $this->membership->removeMember($projectId, $membership['user']['id']);
        }
    }

    public function getAllMemberships(int $project)
    {
        $response = $this->membership->all($project);
        return ($response['memberships']) ? $response['memberships'] : [];
    }

    public function createMembership($project_id, $data){
        $response = $this->membership->create($project_id, $data);
        return isset($response->id) ? $response->id : false;
    }

    public function deleteMembership($membership_id){
        return $this->membership->remove($membership_id);
    }

    public function createUser($data)
    {
        $response = $this->user->create($data);

        return isset($response->id) ? $response->id : false;
    }

    public function getAllPriorities($params = [])
    {
        $priorities = $this->issue_priority->all($params)["issue_priorities"];

        $result = [];

        foreach ($priorities as $priority) {
            $result[$priority['name']] = $priority;
        }

        return $result;
    }

    public function createTimeEntry($data, $username)
    {
        $this->setImpersonateUser($username);
        $response = $this->time_entry->create($data);
        $this->setImpersonateUser(null);

        return isset($response->id) ? $response->id : false;
    }

    public function createAttachment($attachment, $data = [], $username = null)
    {
        $this->setImpersonateUser($username);
        $response = json_decode($this->attachment->upload($attachment, $data), true);
        $this->setImpersonateUser(null);

        return isset($response['upload']) ? $response['upload']['token'] : false;
    }

    public function downloadAttachment(string $id)
    {
        return $this->attachment->download($id);
    }

    /**
     * @param string $name
     *
     * @return \Redmine\Api\AbstractApi
     *
     * @throws \InvalidArgumentException
     */
    public function api($name)
    {
        if (!isset($this->classes[$name])) {
            throw new \InvalidArgumentException();
        }
        if (isset($this->apis[$name])) {
            return $this->apis[$name];
        }
        $c = '\\app\\components\\RedmineApi\\'.$this->classes[$name];
        $this->apis[$name] = new $c($this);

        return $this->apis[$name];
    }
}
