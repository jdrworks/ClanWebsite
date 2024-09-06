<?php
return [
    'aliases' => [
        '@frontendDomain' => 'https://fknamazing.com',
        '@backendDomain'  => 'https://admin.fknamazing.com'
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
            'dsn' => 'mysql:host=localhost;dbname=fknamazing',
            'username' => 'fknamazing',
            'password' => 'r8^yDd7U^qm3d8@8kH',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
