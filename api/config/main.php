<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    //require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
    //require(__DIR__ . '/params-local.php')
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
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => true,
            'on beforeLogin' => function($event) {
                $user = $event->identity; //这里的就是User Model的实例了
                $user->login_at = time();
                $user->login_ip = Yii::$app->request->userIP;
                $user->save();
             },
            'enableSession' => false,
        ],
        'smser' => [
            // 云片网
            'class' => 'daixianceng\smser\YunpianSmser',
            'apikey' => '11bf15c1728ddab4e5501b8c70c225ad', // 请替换成您的apikey
            'useFileTransport' => false
        ],
//         'session' => [
//             // this is the name of the session cookie used for login on the backend
//             'name' => 'advanced-api',
//         ],
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
            'showScriptName' => false,
            'enableStrictParsing' =>false,
        ],
        
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->data = [
                    'code' => $response->getStatusCode(),
                    'message' => $response->statusText,
                    'data_w' => $response->data
                    
                ];
                $response->format = yii\web\Response::FORMAT_JSON;
            },
        ],
        
    ],
    'params' => $params,
];
