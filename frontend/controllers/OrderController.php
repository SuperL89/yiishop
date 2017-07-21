<?php

namespace frontend\controllers;

class OrderController extends \yii\web\Controller
{
    public $layout = false;
    public function actionIndex()
    {
        //$this->layout=false;
        return $this->render('index');
    }
    
    public function actionCheck()
    {
        //$this->layout = false;
        return $this->render('check');
    }

}
