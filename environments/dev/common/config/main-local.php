<?php
return [
    'aliases' => [
        '@frontendDomain' => 'http://fknamazing.test',
        '@backendDomain'  => 'http://admin.fknamazing.test'
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'app\comsole\migrations',
                'bedezign\yii2\audit\migrations',
            ],
        ],
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessRoles' => ['siteadmin'],
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.83.137;dbname=fknamazing',
            'username' => 'fknamazing',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'ssl',
                'host' => 'smtp.sendgrid.net',
                'port' => '465',
                'username' => 'apikey',
                'password' => '',
            ],
        ],
    ],
];
