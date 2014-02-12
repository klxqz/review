<?php

/**
 * @author Коробов Николай wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'shop_review_plugin' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'left_key' => array('int', 11),
        'right_key' => array('int', 11),
        'depth' => array('int', 11, 'null' => 0, 'default' => '0'),
        'parent_id' => array('int', 11, 'null' => 0, 'default' => '0'),
        'domain' => array('varchar', 255),
        'review_id' => array('int', 11, 'null' => 0, 'default' => '0'),
        'datetime' => array('datetime', 'null' => 0),
        'status' => array('enum', "'approved','deleted'", 'null' => 0, 'default' => 'approved'),
        'title' => array('varchar', 64),
        'text' => array('text'),
        'rate' => array('decimal', "3,2"),
        'contact_id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'default' => '0'),
        'name' => array('varchar', 50),
        'email' => array('varchar', 50),
        'site' => array('varchar', 100),
        'auth_provider' => array('varchar', 100),
        'ip' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => 'id',
            'contact_id' => 'contact_id',
            'status' => 'status',
            'parent_id' => 'parent_id',
            'domain' => 'domain',
        ),
    ),
);
