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
            //查询城市
            $place_children = Place::getChildrenList($pid);
            if (count($place_children) > 0) {
                foreach ($place_children as $k => $v) {
                    $place .= Html::tag('option',Html::encode($v['name']),['value'=>$v['id']]);
                }
            }
            //
            
            $option['place'] = $place;
            echo json_encode($option);
        }
    }
    public function actionAjaxPostSelectPlace()
    {
        if(\Yii::$app->request->isAjax){
            $option = array();
            $place_name = $p_name = $name = '';
            $place_name_en = $p_name_en = $name_en = '';
            
            $id = \Yii::$app->request->post('id');
            $pid = \Yii::$app->request->post('pid');
            //查询城市
            $place_info = Place::findOne($id);
            if ($place_info) {
                $name = $place_info->name;
                $name_en = $place_info->name_en;
            }
            //查询父级城市
            $place_parent_info = Place::getParentInfo($pid);
            if ($place_parent_info) {
                $p_name = $place_parent_info->name;
                $p_name_en = $place_parent_info->name_en;
                if ($name_en) {
                    $p_name_en = $place_parent_info->code;
                }
            }
            $place_name = $p_name && $name ? $p_name.' '.$name : $p_name.$name;
            $place_name_en = $p_name_en && $name_en ? $name_en.','.$p_name_en : $name_en.$p_name_en;
            
            $place['name'] = $place_name;
            $place['name_en'] = $place_name_en;
            echo json_encode($place);
        }
    }
}
