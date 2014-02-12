<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopReviewPluginBackendLayout extends shopBackendLayout {

    public function execute() {
        parent::execute();
        $this->assign('page', 'review');
    }

}
