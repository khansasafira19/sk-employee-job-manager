<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'SK-EJM', //nama aplikasi
    'language' => 'id', //bahasa aplikasi
    'timeZone' => 'Asia/Jakarta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'=>[
        'notifications' => [
            'class' => 'machour\yii2\notifications\NotificationsModule',
            // Point this to your own Notification class
            // See the "Declaring your notifications" section below
            'notificationClass' => 'app\common\components\Notification',
            // Allow to have notification with same (user_id, key, key_id)
            // Default to FALSE
            'allowDuplicate' => true,
            // Allow custom date formatting in database
            'dbDateFormat' => 'Y-m-d H:i:s',
            // This callable should return your logged in user Id
            'userId' => function () {
                return Yii::$app->user->identity->getId();
            },
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'adHWzHtY5dNtA29YG76U88tiOlGK9WzD',
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
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'session' => [
            'class' => '\yii\web\Session',
            'name' => 'APPSESSION',
            'savePath' => __DIR__ . '/session',
        ],
        'cookieParams' => [
            'class' => '\yii\web\Cookie',
            'secure' => true,
            'sameSite' => PHP_VERSION_ID >= 70300 ? yii\web\Cookie::SAME_SITE_LAX : null,
        ],
        'formatter' => [
            'dateFormat' => 'd MMM y',
            'locale' => 'id-ID', //your language locale
            'defaultTimeZone' => 'Asia/Jakarta', // time zone
        ],
        'headers' => [
            'class' => '\hyperia\security\Headers',
            'upgradeInsecureRequests' => true,
            'blockAllMixedContent' => true,
            'requireSriForScript' => false,
            'requireSriForStyle' => false,
            'xssProtection' => true,
            'contentTypeOptions' => true,
            'strictTransportSecurity' => [
                'max-age' => 10,
                'includeSubDomains' => true,
                'preload' => false
            ],
            'xFrameOptions' => 'DENY',
            'xPoweredBy' => 'Hyperia',
            'referrerPolicy' => 'no-referrer',
            'cspDirectives' => [
                'connect-src' => "'self'",
                'font-src' => "'self'",
                'frame-src' => "'self'",
                'img-src' => "'self' data:",
                'manifest-src' => "'self'",
                'object-src' => "'self'",
                'prefetch-src' => "'self'",
                'script-src' => "'self' 'unsafe-inline'",
                'style-src' => "'self' 'unsafe-inline'",
                'media-src' => "'self'",
                'form-action' => "'self'",
                'worker-src' => "'self'",
                'report-to' => 'groupname'
            ],
            'featurePolicyDirectives' => [
                'accelerometer' => "'self'",
                'ambient-light-sensor' => "'self'",
                'autoplay' => "'self'",
                'battery' => "'self'",
                'camera' => "'self'",
                'display-capture' => "'self'",
                'document-domain' => "'self'",
                'encrypted-media' => "'self'",
                'fullscreen' => "'self'",
                'geolocation' => "'self'",
                'gyroscope' => "'self'",
                'layout-animations' => "'self'",
                'magnetometer' => "'self'",
                'microphone' => "'self'",
                'midi' => "'self'",
                'oversized-images' => "'self'",
                'payment' => "'self'",
                'picture-in-picture' => "*",
                'publickey-credentials-get' => "'self'",
                'sync-xhr' => "'self'",
                'usb' => "'self'",
                'wake-lock' => "'self'",
                'xr-spatial-tracking' => "'self'"
            ]
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
