<?php

namespace frontend\controllers;

class MemberController extends \yii\web\Controller
{
    public function actionAuth()
    {
        $this->layout =false;
        return $this->render('auth');
    }

}
