<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '1818892128184744',
                    'clientSecret' => '8537ab1af9a98b5b1f5a0fc4851e10bb',
                ],
                // etc.
            ],
        ],
        // ...
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3C5nwc95BRkvmCqr2_c0sEmgxRapIgep',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'db' => $db,

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
              'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                'clientId' => '1818892128184744',
                'clientSecret' => '8537ab1af9a98b5b1f5a0fc4851e10bb',
                'attributeNames' => ['first_name','id','cover'],
                'returnUrl' => 'app\models\controllers\AuthController\actionAuthLoginFb',
              ],
            ],
          ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
    
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
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
