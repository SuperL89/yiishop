<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
//use yii\data\ActiveDataProvider;

class BannersController extends ActiveController
{
    public $modelClass = 'api\models\Banner';
    
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    public function actions()
    {
        $action =  parent::actions(); 
        unset($action['index'],$action['view'],$action['create'],$action['update'],$action['delete']); //所有动作删除
    }

    public function actionIndex(){
        $modelClass = $this->modelClass;  
        $banners = $modelClass::find()->where(['status' => 0])->orderBy('order desc')->all();
        $banner['code'] = '200';
        $banner['msg'] = '';
        $banner['data'] = $banners;
        return $banner;
    }
}
