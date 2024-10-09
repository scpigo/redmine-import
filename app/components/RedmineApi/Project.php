<?php

namespace app\components\RedmineApi;

use app\components\RedmineApi\api\src\Redmine\Api\Project as BaseProject;

class Project extends BaseProject
{
    use GetAllTrait;

    public function updateWithoutSanitize($id, $params)
    {
        $defaults = [
            'id' => $id,
        ];

        $params = array_merge($defaults, $params);

        $xml = $this->prepareParamsXml($params);

        return $this->put('/projects/'.$id.'.xml', $xml->asXML());
    }

    public function createWithoutSanitize($params)
    {
        if (
            !isset($params['name'])
            || !isset($params['identifier'])
        ) {
            throw new \Exception('Missing mandatory parameters');
        }

        $xml = $this->prepareParamsXml($params);

        return $this->post('/projects.xml', $xml->asXML());
    }
}
