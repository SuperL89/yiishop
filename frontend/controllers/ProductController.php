<?php

namespace frontend\controllers;

class ProductController extends \yii\web\Controller
{
    public $layout ='layout2';
    public function actionIndex()
    {
        //$this->layout = false;
        return $this->render('index');
    }
    
    public function actionDetail()
    {
        //$this->layout = false;
        return $this->render('detail');
    }
    

}
