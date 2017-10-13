<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Brand;
use api\models\GoodMb;
use api\models\Freight;
use api\models\Place;
use api\models\GoodMbv;

class MbgoodsController extends ActiveController
{
    public $modelClass = 'api\models\Good';
    
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
    
    public function actionIndex($id){
        $id = intval($id);
        
        if(!$id || $id <= 0){
            $good['code'] = '10001';
            $good['msg'] = '商品id不能为空或小于1';
            return $good;
        }
        $modelClass = $this->modelClass;
        $goods = $modelClass::find()
        ->select(['id','good_num','title','brand_id'])
        ->where(['status' => 0,'id' => $id])
        ->orderBy('order desc')
        ->asArray()
        ->one();
        if(!empty($goods)){
            //获取商品品牌名
            $brand_name = Brand::find()->where(['id' => $goods['brand_id']])->select(['title'])->asArray()->one();
            $goods['brand_name']=$brand_name['title'];
            //获取该商品下的商家报价
            $good_mb = GoodMb::find()->select(['id','user_id','place_id','freight_id','cate_id','brand_id'])->where(['status' => 0,'good_id' => $goods['id']])->asArray()->all();
            $goods['good_mb']=array();
            foreach ($good_mb as $k => $v){
                $goods['good_mb'][$k]['mb_id']=$v['id'];
                //查询商家名称 888（待做）
                $goods['good_mb'][$k]['uesr_name']='Tony';
                //查询商品已售数量 888（待做）
                $goods['good_mb'][$k]['sold_num']='75';
                //查询商品最低运费 888（待做）
                $goods['good_mb'][$k]['freight']='15';
                //查询商品发货地
                $place = Place::find()->select(['title'])->where(['status' => 0,'id' => $v['place_id']])->one();
                $goods['good_mb'][$k]['place'] = $place['title'];
                //查询商品价格以及库存
                $mbv_arr = GoodMbv::find()->select(['price','stock_num'])->where(['status' => 0,'mb_id' => $v['id']])->asArray()->all();
                //商品最低价格 888(有bug) min()为空？
                //$goods['good_mb'][$k]['price_min'] = $this->actionArrvalmin($mbv_arr, 'price');
                $goods['good_mb'][$k]['price_min'] ='100';
                //商品总库存
                $goods['good_mb'][$k]['stock_sum'] = $this->actionArrvalsum($mbv_arr, 'stock_num');             
            }
            
        }else{
            $good['code'] = '10002';
            $good['msg'] = '商品不存在';
            return $good;
        }
        
        $good['code'] = '200';
        $good['msg'] = '';
        $good['data'] = $goods;
        return $good;
    }
    //数组多维转一维 求最小
    public function actionArrvalmin($array,$array_key){
        $arr = array();
        foreach($array as $value) {
            $arr[] = $value[$array_key];
        }
        $arr = array_values($arr);
        //print_r($arr);exit();
        $arr_min = array_search(min($arr),$arr);
        
        return $arr[$arr_min];
    }
    //数组多维转一维 求和
    public function actionArrvalsum($array,$array_key){
        $arr = array();
        foreach($array as $value) {
            $arr[] = $value[$array_key];
        }
        $arr_sum = array_sum($arr);
        return $arr_sum;
    }
}
