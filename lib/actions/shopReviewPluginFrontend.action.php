<?php

class shopReviewPluginFrontendAction extends shopFrontendAction {

    protected $plugin_id = array('shop', 'review');

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $domain = wa()->getRouting()->getDomain();
        $reviews_model = new shopReviewPluginModel();
        $reviews = $reviews_model->getFullTree(
                $domain, 0, null, 'datetime DESC', array('escape' => true)
        );

        $config = wa()->getConfig();
        $this->view->assign(array(
            'reviews' => $reviews,
            'reviews_count' => $reviews_model->count($domain, false),
            'reply_allowed' => true,
            'auth_adapters' => $adapters = wa()->getAuthAdapters(),
            'request_captcha' => $app_settings_model->get($this->plugin_id, 'request_captcha'),
            'require_authorization' => $app_settings_model->get($this->plugin_id, 'require_authorization'),
        ));
        
        $storage = wa()->getStorage();
        $current_auth = $storage->read('auth_user_data');
        $current_auth_source = $current_auth ? $current_auth['source'] : shopProductReviewsModel::AUTH_GUEST;

        $this->view->assign('current_auth_source', $current_auth_source);
        $this->view->assign('current_auth', $current_auth, true);
    }

}
