<?php

namespace frontend\controllers;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'layout1';
        return $this->render('index');
    }

}
