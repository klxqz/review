<?php

class shopReviewPlugin extends shopPlugin {

    public function routing($routes) {
        $url = $this->getSettings('page_url');
        $review_routes = array(
            $url => 'frontend/',
            'review/add/' => 'frontend/add/',
        );
        return $review_routes;
    }

    public function backendMenu() {
        if ($this->getSettings('status')) {
            $html = '<li ' . (waRequest::get('plugin') == $this->id ? 'class="selected"' : 'class="no-tab"') . '>
                        <a href="?plugin=review">Отзывы о магазине</a>
                    </li>';
            return array('core_li' => $html);
        }
    }

}
