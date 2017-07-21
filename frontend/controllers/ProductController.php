<?php

namespace frontend\controllers;

class ProductController extends \yii\web\Controller
{
    public $layout =false;
    //产品列表页
    public function actionIndex()
    {
        //$this->layout = false;
        return $this->render('index');
    }
    //产品详情页
    public function actionDetail()
    {
        //$this->layout = false;
        return $this->render('detail');
    }
    

}
