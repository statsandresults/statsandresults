<?php

$config = [
    'id' => 'app',
    'defaultRoute' => 'main/default/index',
    'layout' => 'main',
    'layoutPath' => '@app/modules/main/layouts',
    'homeUrl' => 'http://' . $_SERVER['SERVER_NAME'] . '/',
    'components' => [
        /*
        'user' => [
                    'identityClass' => 'app\modules\user\models\User',
                    'enableAutoLogin' => true,
                    'loginUrl' => ['user/default/login'],
                ],
        */
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'request' => [
            'enableCookieValidation' => true,
            'cookieValidationKey' => 'KH65KJisd',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
//            'baseUrl' => 'http://' . $_SERVER['SERVER_NAME'].'/',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/modules/user/views'
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;