<?php
return [
    'sourceLanguage' => 'en',
    'language' => 'ru',
    'name' => env('NAME'),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        \common\bootstrap\SetUp::class,
    ],
    'components' => [
        'cache' => [
            //'class' => 'yii\caching\MemCache',
            'class' => 'yii\caching\FileCache',
            //'useMemcached' => true
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}',
        ],
    ],
];
