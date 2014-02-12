<?php

return array(
    'name' => 'Отзывы о магазине',
    'description' => 'Позволяет покупателям оставить отзыв о магазине',
    'vendor' => '985310',
    'version' => '1.0.0',
    'img' => 'img/review.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        //'frontend_nav' => 'frontendNav',
        'routing' => 'routing',
        'backend_menu' => 'backendMenu',
    ),
);
//EOF
