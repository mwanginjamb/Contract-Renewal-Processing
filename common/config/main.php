<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => \yii\rbac\DbManager::class
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'utility' => [
            'class' => \common\Library\Utility::class,
        ],
        'sharepoint' => [
            'class' => \common\Library\Sharepoint::class
        ]
    ],
];
