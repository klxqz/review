<?php

class shopReviewPluginBackendAddController extends waJsonController
{
    /**
     * @var shopProductReviewsModel
     */
    protected $review_model;

    public function __construct()
    {
        $this->review_model = new shopReviewPluginModel();
        $this->author = $this->getUser();
        $this->view = wa()->getView();
    }

    public function execute()
    {
        $data = $this->getReqiestData();
        if ($this->errors = $this->review_model->validate($data)) {
            return false;
        }

        $id = $this->review_model->add($data, $data['parent_id']);
        if (!$id) {
            throw new waException("Error in adding review");
        }

        $data['id'] = $id;
        $data['author'] = $this->getResponseAuthorData();

        $this->view->assign('review', $data);
        $this->view->assign('reply_allowed', true);
        $this->response['id'] = $data['id'];
        $this->response['parent_id'] = $data['parent_id'];
        $this->response['html'] = $this->view->fetch('plugins/review/templates/actions/backend/include.review.html');
    }

    protected function getReqiestData()
    {
        $domain = waRequest::post('domain', null, waRequest::TYPE_STRING);
        $parent_id  = waRequest::post('parent_id', 0, waRequest::TYPE_INT);
        $rate = 0;

        if (wa()->getEnv() == 'backend' && !$parent_id) {
            throw new waException(_w("Writing a review to product is available just on frontend"));
        }

        if (!$domain && !$parent_id) {
            throw new waException("Can't add comment: unknown product for review or review for reply");
        }
        if (!$domain) {
            $parent_comment = $this->review_model->getById($parent_id);
            $domain = $parent_comment['domain'];
            $rate = waRequest::post('rate', 0, waRequest::TYPE_INT);
        }
        $text = waRequest::post('text',   null, waRequest::TYPE_STRING_TRIM);
        $title = waRequest::post('title', null, waRequest::TYPE_STRING_TRIM);
        return array(
            'domain'  => $domain,
            'parent_id' => $parent_id,
            'title' => $title,
            'text' => $text,
            'rate' => $rate,
            'contact_id' => $this->author->getId(),
            'auth_provider' => shopReviewPluginModel::AUTH_USER,
            'datetime' => date('Y-m-d H:i:s'),
            'status' => shopReviewPluginModel::STATUS_PUBLISHED
        );
    }

    protected function getResponseAuthorData()
    {
        return array(
            'id' => $this->author->getId(),
            'name' => $this->author->getName(),
            'photo' => $this->author->getPhoto(50)
        );
    }
}