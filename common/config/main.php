<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'qiniu'=> [
            'class' => 'crazyfd\qiniu\Qiniu',
            'accessKey' => 'r1Mi9A9K5ACE7UrBB2Y5Hk5xF05OABhzgGt_mEQz',
            'secretKey' => 'EpTrcmszAYQUfjQ2FlgV7yV2E0B9u65YH2NciqQB',
            'domain' => 'http://oz588ykkh.bkt.gdipper.com/',
            'bucket' => 'hexin-image',
        ],
    ],
    'language'=>'zh_CN',
    "timeZone" => "Asia/Shanghai",
];
