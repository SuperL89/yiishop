<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use common\models\Place;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class PlaceAjaxController extends GoodMbController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionAjaxPostChildrenPlace()
    {
        if(\Yii::$app->request->isAjax){
            $option = array();
            $place = '';
            
            $pid = \Yii::$app->request->post('pid');
            //查询分类
            $place_children = Place::getChildrenList($pid);
            if (count($place_children) > 0) {
                foreach ($place_children as $k => $v) {
                    $place .= Html::tag('option',Html::encode($v['name']),['value'=>$v['id']]);
                }
            }
            
            $option['place'] = $place;
            echo json_encode($option);
        }
    }
}
