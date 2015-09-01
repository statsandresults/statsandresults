<?php
use yii\helpers\ArrayHelper;

$params = ArrayHelper::merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\modules\main\Bootstrap',
        'app\modules\contact\Bootstrap',
        'app\modules\bet\Bootstrap'
    ],
    'name' => 'Stats And Result',
    'modules' => [
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'user' => [
            //'class' => 'app\modules\user\Module',
            'class' => 'dektrium\user\Module',
            'controllerMap' => [
                'registration' => 'app\modules\user\controllers\RegistrationController'
            ],
            'modelMap' => [
                'RegistrationForm' => 'app\modules\user\models\RegistrationForm',
            ]
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
        ],
        'country' => [
            'class' => 'app\modules\country\Module',
        ],
        'contact' => [
            'class' => 'app\modules\contact\Module',
        ],
        'bet' => [
            'class' => 'app\modules\bet\Module',
        ]
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
/*                '' => 'main/default/index',*/
/*                'contact' => 'contact/default/index',*/

                '<_a:error>' => 'main/default/<_a>',
                '<_a:(login|logout)>' => 'user/default/<_a>',

                '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
                '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/<_a>',
                '<_m:[\w\-]+>' => '<_m>/default/index',
                '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                ]/*,
                'db' => [
                    'class' => 'yii\log\DbTarget',
                ],*/
            ]
        ],
    ],
    'params' => $params,
];