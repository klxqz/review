<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopReviewPluginBackendReviewsAction extends waViewAction {

    public function execute() {
        $domain = waRequest::get('domain', '', waRequest::TYPE_STRING_TRIM);
        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);
        $total_count = waRequest::get('total_count', null, waRequest::TYPE_INT);
        $lazy = waRequest::get('lazy', false, waRequest::TYPE_INT);

        $reivew_model = new shopReviewPluginModel();
        $reviews_per_page = $this->getConfig()->getOption('reviews_per_page_total');

        $where = array();
        if ($domain) {
            $where = array('domain' => 'sub.mir-bruk');
        }
        $reviews = $reivew_model->getList('*,is_new,contact,domain', array(
            'offset' => $offset,
            'limit' => $reviews_per_page,
            'where' => $where
                )
        );

        // TODO: move to model


        $this->view->assign(array(
            'total_count' => $total_count ? $total_count : $reivew_model->count($domain, false),
            'count' => count($reviews),
            'offset' => $offset,
            'reviews' => $reviews,
            'current_author' => shopReviewPluginModel::getAuthorInfo(wa()->getUser()->getId()),
            'reply_allowed' => true,
            'lazy' => $lazy,
            'sidebar_counters' => array(
                'new' => $reivew_model->countNew(!$offset)
            )
        ));
    }

}
