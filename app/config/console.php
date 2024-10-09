<?php

use app\components\RedmineClient;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer'            => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => env('MAIL_FILE_TRANSPORT') == 'true',
            'transport'        => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => env('MAILER_HOST'),
                'username'   => env('MAILER_USERNAME'),
                'password'   => env('MAILER_PASSWORD'),
                'port'       => env('MAILER_POST'),
                'encryption' => env('MAILER_ENCRYPTION'),
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'redmine_old'       => function () {
            return new RedmineClient(
                env('REDMINE_OLD_URL'),
                env('REDMINE_OLD_KEY')
            );
        },
        'redmine_new'       => function () {
            return new RedmineClient(
                env('REDMINE_NEW_URL'),
                env('REDMINE_NEW_KEY')
            );
        },
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
