<?php

namespace app\components\RedmineApi;


use app\components\RedmineApi\api\src\Redmine\Api\AbstractApi;

/**
 * Class GetAllTrait
 * @package app\components\RedmineApi
 * @method mixed get($endpoint)
 * @method array sanitizeParams($defaults, $params)
 */
trait GetAllTrait
{
    /**
     * Retrieves all the elements of a given endpoint (even if the
     * total number of elements is greater than 100).
     *
     * @param string $endpoint API end point
     * @param array  $params   optional parameters to be passed to the api (offset, limit, ...)
     *
     * @return array elements found
     */
    protected function retrieveAll($endpoint, array $params = array())
    {
        if (empty($params)) {
            return $this->retrieveAllWithoutLimit($endpoint, []);
        }

        $params = $this->sanitizeParams([], $params);

        if (!isset($params['limit'])) {
            return $this->retrieveAllWithoutLimit($endpoint, $params);
        }

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        return AbstractApi::retrieveAll($endpoint, $params);
    }

    protected function retrieveAllWithoutLimit($endpoint, $params)
    {
        $result = [];

        $first = true;
        $total = null;
        $params['limit'] = 100;
        $params['offset'] = 0;
        do {
            $newDataSet = (array)$this->get($endpoint . '?' . http_build_query($params));

            $result = array_merge_recursive($result, $newDataSet);

            if ($first) {
                $total = $result['total_count'];
                $first = false;
            }

            $params['offset'] += $params['limit'];
        } while ($total > $newDataSet['offset'] + $params['limit']);

        $result['limit'] = $total;

        return $result;
    }
}
