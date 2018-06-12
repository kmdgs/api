<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'default',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\common\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        //api v1版本
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'api\modules\v2\Module',
        ],
    ],

    'components' => [
        'user' => [
            //设置认证类
            'identityClass' => 'api\common\models\user\User',
            //是否基于cookie的登录.
            'enableAutoLogin' => true,
            //是否使用会话持续跨多个请求身份验证状态。设置这个属性是假的如果您的应用程序是无状态的,这通常是基于rest的api
            'enableSession' => false,
        ],
        //缓存路径
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],

       
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        //链接配置
        //enableStrictParsing 是否启用严格解析
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/article']],
                'GET,HEAD article/<id:\d+>' => 'article/view',
                'GET,HEAD goods/<id:\d+>' => 'goods/view',
                'GET,HEAD adminuser/<id:\d+>' => 'adminuser/view',
                'GET,HEAD order/<id:\d+>' => 'order/view',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'adminuser',
                    'except' => ['delete', 'create', 'view', 'index'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'GET register' => 'register',
                        'GET me' => 'me',
                        'PATCH PUT update' => 'update'
                    ]

                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'favorite',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST create' => 'create',
                        'DELETE <id>' => 'delete',
                        'GET whether' => 'whether',
                    ]
                ],
                'GET,HEAD poster/<id:\d+>' => 'poster/view',
            ],
        ],


    ],

    'params' => $params,
];
