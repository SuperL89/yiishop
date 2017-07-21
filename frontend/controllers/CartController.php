<?php

namespace frontend\controllers;

class CartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout='layout1';
        return $this->render('index');
    }

}
