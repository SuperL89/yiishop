<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;


class SearchkeywordsController extends ActiveController
{
    public $modelClass = 'api\models\SearchKeywords';
    
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
        $keywords = $modelClass::find()->select(['title'])->where(['status' => 0])->orderBy('order desc')->all();
        $keyword['code'] = '200';
        $keyword['msg'] = '';
        $keyword['data'] = $keywords;
        return $keyword;
    }
}
