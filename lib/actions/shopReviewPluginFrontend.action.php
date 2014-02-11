<?php

class shopReviewPluginFrontendAction extends shopFrontendAction {

    protected $plugin_id = array('shop', 'review');

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $contact_id = wa()->getUser()->getId();
        $title = waRequest::post('title');
        $rate = waRequest::post('rate');
        $text = waRequest::post('text');


        $this->view->assign('require_authorization', '1');
        $this->view->assign('is_captcha', '1');
    }

}
