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
            //'errorAction' => 'v1/site/error',
        ],
        // other config
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,    
            'enableStrictParsing' =>true,
            'rules' => [
                '/'=>'default/index',//这是个坑  如不配置则enableStrictParsing开启后全部404 mbd
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/user',
                        'v1/viewgoods',
                        'v1/listgoods',
                        'v1/categorys',
                        'v1/banners',
                        'v1/mbgoods',
                        'v1/mbvgoods',
                        'v1/searchgoods',
                        'v1/searchbarcode',
                        'v1/searchkeywords',
                    ],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST index' => 'index',
                        'POST register' => 'register',
                        'POST login' => 'login',
                        'GET signup-test' => 'signup-test',
                        'POST resetpwd' => 'resetpwd',
                        'POST send-verifycode' => 'send-verifycode',
                        'POST user-profile' => 'user-profile',
                        'POST update-profile' => 'update-profile',
                        'POST reset-password' => 'reset-password',
                        'POST modify-username' => 'modify-username',
                        'POST update-username' => 'update-username',
                        'POST receiving-address' => 'receiving-address',
                        'POST create-address' => 'create-address',
                        'POST update-address' => 'update-address',
                        'POST get-place' => 'get-place',
                        'POST get-category' => 'get-category',
                        'POST apply-business' => 'apply-business',
                        'POST update-business' => 'update-business',
                        'POST create-freight' => 'create-freight',
                        'POST update-freight' => 'update-freight',
                        'POST receiving-freight' => 'receiving-freight',
                        'POST good-order' => 'good-order','POST good-order' => 'good-order',
                        'POST get-good-order' => 'get-good-order',
                        'POST get-user-order' => 'get-user-order',
                        'POST get-business-order' => 'get-business-order',
                        'POST confirm-receipt' => 'confirm-receipt',
                        'POST business-delivery' => 'business-delivery',
                        'POST business-cancel' => 'business-cancel',
                        'POST get-business-star' => 'get-business-star',
                        'POST user-tradelog' => 'user-tradelog',
                        'POST user-wallet' => 'user-wallet',
                        'POST user-pay-set' => 'user-pay-set',
                        'POST user-paypassword-set' => 'user-paypassword-set',
                        'POST user-paypassword-reset' => 'user-paypassword-reset',
                        'POST pay-type' => 'pay-type',
                        'POST payment-order' => 'payment-order',
                        'POST business-goodlist' => 'business-goodlist',
                        'POST get-express' => 'get-express',
                        'POST business-goodset' => 'business-goodset',
                        'POST business-create-good' => 'business-create-good',
                        'POST business-profile' => 'business-profile',
                        'POST business-update-good' => 'business-update-good',
                        'POST business-goodview' => 'business-goodview',
                    ]
                ],
            ]
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
