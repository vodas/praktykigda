<?php

$params = require(__DIR__ . '/params.php');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
             'downloadAction' => 'gridview/export/download'
             //'i18n' => []
        ],
        'webapi' => [
            'class' => 'app\modules\webapi\Module',
        ],
    ],
    'language' => 'pl',
    'sourceLanguage' => 'en',

    'aliases' => [
        '@twitter' => '@vendor/yiitwitteroauth',
    ],

    'components' => [



        'spoolmailer' => [
            'class' => 'vendor\spoolmailer\SpoolMailer',
            'host' => 'mailtrap.io',
            'username' => '389479760d77ba09a',
            'password' => 'deacbea15d81e3',
            'port' => '2525',
            ],


        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
            ],
        ],

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'woCZLZIKcYCNFQCSNgrZeEJfl',
                    'consumerSecret' => 'dj1FsjkimaWaOqKKo5PpSWrSZJrO20D66d3SVjdMte9VUKNHmV',
                ],
            ],
        ],

        'twitter' => [
            'class' => '\vendor\yiitwitteroauth\YiiTwitter',
            'consumer_key' => 'woCZLZIKcYCNFQCSNgrZeEJfl',
            'consumer_secret' => 'dj1FsjkimaWaOqKKo5PpSWrSZJrO20D66d3SVjdMte9VUKNHmV',
            'callback' => 'http://praktyki.gda.dev/login/tweetercallback',
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [

                [
                    'pattern' => '<module:webapi>/<controller:\w+>/<action:\w+>/<id:\d+>',
                    'route' => '<module>/<controller>/<action>/<id>',
                    'suffix' => '.json',

                ],
                'webapi/order/' => 'webapi/order/read-all',
                'webapi/order/<id:\d+>' => 'webapi/order/read-specific',
                'webapi/product/<id:\d+>' => 'webapi/product/view',
                'webapi/product/update/<id:\d+>' => 'webapi/product/update',
                'webapi/product/delete/<id:\d+>' => 'webapi/product/delete',
                'webapi/restaurant/<id:\d+>' => 'webapi/restaurant/view',
                'webapi/restaurant/update/<id:\d+>' => 'webapi/restaurant/update',
                'webapi/restaurant/delete/<id:\d+>' => 'webapi/restaurant/delete',
                'webapi/review/<id:\d+>' => 'webapi/review/view',
                'webapi/review/update/<id:\d+>' => 'webapi/review/update',
                'webapi/review/delete/<id:\d+>' => 'webapi/review/delete',
                'webapi/warehouse/<id:\d+>' => 'webapi/warehouse/view',
                'webapi/warehouse/update/<id:\d+>' => 'webapi/warehouse/update',
                'webapi/warehouse/delete/<id:\d+>' => 'webapi/warehouse/delete',

            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'X00fSoCDnl7ziWUTdtyT2p09RMi-kohz',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'fileTransportPath' => '@app/mailspool',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mailtrap.io',
                'username' => '389479760d77ba09a',
                'password' => 'deacbea15d81e3',
                'port' => '2525',
                //'encryption' => 'cram-md5',
            ],
        ],


        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';


}

if (YII_ENV_PROD) {
    $config['components']['spoolmailer']['host']= '';
    $config['components']['spoolmailer']['port']= '';
    $config['components']['spoolmailer']['username']= '';
    $config['components']['spoolmailer']['password']= '';
}

if (YII_ENV_TEST) {
    $config['components']['spoolmailer']['host']= '';
    $config['components']['spoolmailer']['port']= '';
    $config['components']['spoolmailer']['username']= '';
    $config['components']['spoolmailer']['password']= '';
}



return $config;

