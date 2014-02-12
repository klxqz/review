<?php

class shopReviewPluginBackendChangeStatusController extends waJsonController
{
    public function execute()
    {
        $review_id = waRequest::post('review_id', null, waRequest::TYPE_INT);
        if (!$review_id) {
            throw new waException("Unknown review id");
        }

        $status = waRequest::post('status', '', waRequest::TYPE_STRING_TRIM);
        if (
            $status == shopReviewPluginModel::STATUS_DELETED ||
            $status == shopReviewPluginModel::STATUS_PUBLISHED
        ) {
            $review_model = new shopReviewPluginModel();
            $review_model->changeStatus($review_id, $status);
        }
    }
}