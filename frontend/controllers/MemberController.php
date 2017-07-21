<?php

namespace frontend\controllers;

class MemberController extends \yii\web\Controller
{
    public function actionAuth()
    {
        $this->layout ='layout2';
        return $this->render('auth');
    }

}
