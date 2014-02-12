<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopReviewPluginBackendReviewsAction extends waViewAction {

    public function execute() {
        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);
        $total_count = waRequest::get('total_count', null, waRequest::TYPE_INT);
        $lazy = waRequest::get('lazy', false, waRequest::TYPE_INT);

        $reivew_model = new shopReviewPluginModel();
        $reviews_per_page = $this->getConfig()->getOption('reviews_per_page_total');

        /*
          $reviews = $product_reivews_model->getList(
          $offset,
          $reviews_per_page,
          array('is_new' => true)
          );
         */
        $reviews = $reivew_model->getList('*,is_new,contact,domain', array(
            'offset' => $offset,
            'limit' => $reviews_per_page,
            //'where' => array('domain' => 'sub.mir-bruk')
                )
        );

        // TODO: move to model


        $this->view->assign(array(
            'total_count' => $total_count ? $total_count : $reivew_model->count(/*'sub.mir-bruk'*/),
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
