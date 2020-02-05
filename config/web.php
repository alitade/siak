<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db1 = require __DIR__ . '/db1.php';
$db2 = require __DIR__ . '/db2.php';

$config = [
    'language'=>'id',
    'id' => 'basic',
    'name'=>'SIAKAD',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
        #'@api'=>\yii\helpers\Url::to
    ],
    'components' => [
        'vd'=>['class'=>'app\components\vdComponent'],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    //'fileMap' => ['app' => 'app.php','app/error' => 'error.php',],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6QQ_elFnOfRwyX4PzQABXRWJb2jjOu_X',
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
        'db1' => $db1,
        'db2' => $db2,
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' =>'skin-black',
                ],
            ],
        ],
        #/*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //  'rules' => [],
        ],

        #*/
    ],
    'modules'=>[
        #==================================================
        'gridview' => ['class' => '\kartik\grid\Module',],
        'datecontrol' => ['class' => '\kartik\datecontrol\Module',],
        'treemanager' =>  ['class' => '\kartik\tree\Module',],
        #==================================================
        'admin' => [
            #'class' => 'app\modules\admin\Module',
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'app\models\User',
                    'idField' => 'id',
                    'usernameField' => 'username',
                    'searchClass' => 'app\models\UsersSearch'
                ],
            ],
        ],
        #===== CUSTOM ===========
        #'v1' => ['class' => 'app\modules\v1\Module',],
        #===== END ===========

    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'admin/*',
            '*'
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => ['class' => 'dee\gii\generators\crud\Generator',],
            'angular' => ['class' => 'dee\gii\generators\angular\Generator'],
            'mvc' => ['class' => 'dee\gii\generators\mvc\Generator'],
            'migration' => ['class' => 'dee\gii\generators\migration\Generator'],
        ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','*'],
    ];
}

return $config;
