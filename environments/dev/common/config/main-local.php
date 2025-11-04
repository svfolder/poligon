<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'mysql' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_SYSTEM'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'knowledge' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . env('DB_KNOW_HOST') . ';dbname=' . env('DB_KNOW_NAME'),
            'username' => env('DB_KNOW_USERNAME'),
            'password' => env('DB_KNOW_PASSWORD'),
            'charset' => 'utf8',
        ],
        'kit' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . env('DB_KIT_HOST') . ';dbname=' . env('DB_KIT_NAME'),
            'username' => env('DB_KIT_USERNAME'),
            'password' => env('DB_KIT_PASSWORD'),
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
            'messageConfig' => [
                'from' => ['support@example.com' => env('NAME')]
            ],
        ],
    ],
];
