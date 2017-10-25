<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `v1` module
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
