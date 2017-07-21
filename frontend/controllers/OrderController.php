<?php

namespace frontend\controllers;

class OrderController extends \yii\web\Controller
{
    //public $layout = false;
    public function actionIndex()
    {
        $this->layout='layout2';
        return $this->render('index');
    }
    
    public function actionCheck()
    {
        $this->layout = 'layout1';
        return $this->render('check');
    }

}
