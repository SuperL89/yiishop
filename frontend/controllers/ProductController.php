<?php

namespace frontend\controllers;

class ProductController extends \yii\web\Controller
{
    public $layout =false;
    //��Ʒ�б�ҳ
    public function actionIndex()
    {
        //$this->layout = false;
        return $this->render('index');
    }
    //��Ʒ����ҳ
    public function actionDetail()
    {
        //$this->layout = false;
        return $this->render('detail');
    }
    

}
