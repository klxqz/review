<?php

class shopReviewPluginFrontendAddController extends waJsonController {

    private $model;

    public function __construct() {
        $this->model = new shopReviewPluginModel();
        $this->author = $this->getUser();
    }


    public function execute() {
        $domain = wa()->getRouting()->getDomain();

        $data = $this->getData($domain);
        $errors = $this->model->validate($data);
        $this->errors += $errors;
        if ($this->errors) {
            return false;
        }
        $id = $this->model->add($data, $data['parent_id']);
        if (!$id) {
            throw new waException("Error in adding review");
        }

        $count = waRequest::post('count', 0, waRequest::TYPE_INT) + 1;

        $this->response = array(
            'id' => $id,
            'parent_id' => $this->getParentId(),
            'count' => $count,
            'html' => $this->renderTemplate(array(
                'review' => $this->model->getReview($id, true),
                'reply_allowed' => true,
                'ajax_append' => true), 'file:review.html'
            ),
            'review_count_str' => _w(
                    '%d review for ', '%d reviews for ', $count
            )
        );
    }

    private function renderTemplate($assign, $template) {
        $theme = waRequest::param('theme', 'default');
        $theme_path = wa()->getDataPath('themes', true) . '/' . $theme;
        if (!file_exists($theme_path) || !file_exists($theme_path . '/theme.xml')) {
            $theme_path = wa()->getAppPath() . '/themes/' . $theme;
        }
        $view = wa()->getView(array('template_dir' => $theme_path));
        $view->assign($assign);
        return $view->fetch($template);
    }

    private function getParentId() {
        return waRequest::post('parent_id', 0, waRequest::TYPE_INT);
    }

    private function getData($domain) {
        $parent_id = $this->getParentId();
        $rate = waRequest::post('rate', null, waRequest::TYPE_INT);
        if (!$domain) {
            $parent_comment = $this->model->getById($parent_id);
            $domain = $parent_comment['domain'];
        }
        $text = waRequest::post('text', null, waRequest::TYPE_STRING_TRIM);
        $title = waRequest::post('title', null, waRequest::TYPE_STRING_TRIM);

        return array(
            'domain' => $domain,
            'parent_id' => $parent_id,
            'text' => $text,
            'title' => $title,
            'rate' => $rate && !$parent_id ? $rate : null,
            'datetime' => date('Y-m-d H:i:s'),
            'status' => shopReviewPluginModel::STATUS_PUBLISHED
                ) + $this->getContactData();
    }

    private function getContactData() {
        $contact_id = (int) $this->getUser()->getId();
        $adapter = 'user';

        if (!$contact_id) {
            $adapter = waRequest::post('auth_provider', 'guest', waRequest::TYPE_STRING_TRIM);
            if (!$adapter || $adapter == 'user') {
                $adapter = 'guest';
            };
        }

        if ($adapter == 'guest') {
            $data['name'] = waRequest::post('name', '', waRequest::TYPE_STRING_TRIM);
            $data['email'] = waRequest::post('email', '', waRequest::TYPE_STRING_TRIM);
            $data['site'] = waRequest::post('site', '', waRequest::TYPE_STRING_TRIM);
            $this->getStorage()->del('auth_user_data');
        } else if ($adapter != 'user') {
            $auth_adapters = wa()->getAuthAdapters();
            if (!isset($auth_adapters[$adapter])) {
                $this->errors[] = _w('Invalid auth provider');
            } elseif ($user_data = $this->getStorage()->get('auth_user_data')) {
                $data['name'] = $user_data['name'];
                $data['email'] = '';
                $data['site'] = $user_data['url'];
            } else {
                $this->errors[] = _w('Invalid auth provider data');
            }
        }

        $data['auth_provider'] = $adapter;
        $data['contact_id'] = $contact_id;

        return $data;
    }

}
