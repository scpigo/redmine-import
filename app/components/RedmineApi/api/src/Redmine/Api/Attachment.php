<?php

namespace app\components\RedmineApi\api\src\Redmine\Api;

/**
 * Attachment details.
 *
 * @see   http://www.redmine.org/projects/redmine/wiki/Rest_Attachments
 *
 * @author Kevin Saliou <kevin at saliou dot name>
 */
class Attachment extends AbstractApi
{
    /**
     * Get extended information about an attachment.
     *
     * @see http://www.redmine.org/projects/redmine/wiki/Rest_Attachments
     *
     * @param string $id the attachment number
     *
     * @return array information about the attachment
     */
    public function show($id)
    {
        return $this->get('/attachments/'.urlencode($id).'.json');
    }

    /**
     * Get attachment content as a binary file.
     *
     * @param string $id the attachment number
     *
     * @return string the attachment content
     */
    public function download($id)
    {
        return $this->get('/attachments/download/'.urlencode($id), false);
    }

    /**
     * Upload a file to redmine.
     *
     * @see http://www.redmine.org/projects/redmine/wiki/Rest_api#Attaching-files
     * available $params :
     * - filename: filename of the attachment
     *
     * @param string $attachment the attachment content
     * @param array  $params     optional parameters to be passed to the api
     *
     * @return array information about the attachment
     */
    public function upload($attachment, $params = [])
    {
        return $this->post('/uploads.json?'.http_build_query($params), $attachment);
    }

    /**
     * Delete an attachment
     *
     * @see https://www.redmine.org/projects/redmine/wiki/Rest_Attachments#DELETE
     *
     * @param int $id id of the attachment
     *
     * @return false|\SimpleXMLElement|string
     */
    public function remove($id)
    {
        return $this->delete('/attachments/'.$id.'.xml');
    }
}
