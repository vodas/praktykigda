<?php

if (YII_ENV_DEV) {
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=restaurant_application',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    ];
}

if (YII_ENV_TEST) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=restaurant_application',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}

if (YII_ENV_PROD) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=restaurant_application',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}

