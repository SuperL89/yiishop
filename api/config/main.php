<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
//         'request' => [
//             'csrfParam' => '_csrf-backend',
//         ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // other config
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' =>false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/banners','v1/categorys']],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
//         'response' => [
//             'class' => 'yii\web\Response',
//             'on beforeSend' => function ($event) {
//                 $response = $event->sender;
//                 if ($response->data !== null && !empty(Yii::$app->request->get('suppress_response_code'))) {
//                     $response->data = [
//                         'success' => $response->isSuccessful,
//                         'data' => $response->data,
//                     ];
//                     $response->statusCode = 200;
//                 }
//             },
//         ]
        
    ],
    'params' => $params,
];
