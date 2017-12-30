<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use common\models\Category;
use common\models\Brand;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryAjaxController extends GoodController
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
    public function actionAjaxPostChildrenCate()
    {
        //print_r('23123123123');exit();
        if(\Yii::$app->request->isAjax){
            $option = array();
            $cate = '';
            $brand = '';
            
            $pid = \Yii::$app->request->post('pid');
            $cateid = \Yii::$app->request->post('cateid');
            $level = \Yii::$app->request->post('level');
            //查询分类
            $cate_children = Category::getChildrenList($pid, $level);
            if (count($cate_children) > 0) {
                foreach ($cate_children as $k => $v) {
                    $cate .= Html::tag('option',Html::encode($v['title']),['value'=>$v['id']]);
                }
            }
            //查询分类品牌
            $brand_info = Brand::getBrandByCate($cateid);
            if (count($brand_info) > 0) {
                foreach ($brand_info as $brandKey => $brandValue) {
                    $brand .= Html::tag('option',Html::encode($brandValue['title']),['value'=>$brandValue['id']]);
                }
            }
            
            $option['cate'] = $cate;
            $option['brand'] = $brand;
            echo json_encode($option);
        }
    }
}
