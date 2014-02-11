<?php

class shopReviewPluginFrontendAddController extends waJsonController {

    protected $plugin_id = array('shop', 'review');

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $contact_id = wa()->getUser()->getId();
        $title = waRequest::post('title');
        $rate = waRequest::post('rate');
        $text = waRequest::post('text');

        $review = array(
            'contact_id' => $contact_id,
            'title' => $title,
            'rate' => $rate,
            'text' => $text,
            'date' => waDateTime::date('Y-m-d H:i:s')
        );
        
        $review_model = new shopReviewPluginModel();
        $review_model->insert($review);
    }

}
