<?php

namespace frontend\controllers;

class CartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout=false;
        return $this->render('index');
    }

}
