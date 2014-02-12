<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopReviewPluginBackendAction extends waViewAction {

    public function execute() {
        $domains = wa()->getRouting()->getDomains();
        $this->view->assign('domains', $domains);
        $this->setLayout(new shopReviewPluginBackendLayout());
    }

}
