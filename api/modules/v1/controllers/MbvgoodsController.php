<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use api\models\GoodImage;
use api\models\GoodMb;
use api\models\GoodMbv;


class MbvgoodsController extends ActiveController
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
    
    public function actionIndex($id,$mbid){
        $id = intval($id);
        $mbid = intval($mbid);
        if(!$id || $id <= 0 || !$mbid || $mbid <= 0){
            $good['code'] = '10001';
            $good['msg'] = '商品id不能为空或小于1';
            return $good;
        }
        //查询是否有该报价
        $good_mb = GoodMb::find()->select(['id'])->where(['status' => 0,'good_id' => $id,'id' => $mbid])->asArray()->one();
        if(empty($good_mb)){
            $good['code'] = '10003';
            $good['msg'] = '该商品或报价不存在，请核实';
            return $good;
        }
        $modelClass = $this->modelClass;
        $goods = $modelClass::find()
        ->select(['id','title'])
        ->where(['status' => 0,'id' => $id])
        ->orderBy('order desc')
        ->asArray()
        ->one();
        if(!empty($goods)){
            //获取商品图片
            $image_url = GoodImage::find()->select(['image_url'])->where(['good_id' => $goods['id']])->asArray()->one();
            $goods['image_url']=$image_url['image_url'];
            //获取该商品的 型号、价格、库存、条形码 集合
            $mbv_arr = GoodMbv::find()->select(['id','model_text','price','stock_num','bar_code'])->where(['mb_id' => $mbid])->asArray()->all();
            //print_r($mbv_arr);exit();
            $goods['good_mbv']=array();
            foreach ($mbv_arr as $k => $v){
                //型号
                $goods['good_mbv'][$k]['model_text']=$v['model_text'];
                //价格
                $goods['good_mbv'][$k]['price']=$v['price'];
                //库存
                $goods['good_mbv'][$k]['stock_num']=$v['stock_num'];
                //条形码
                $goods['good_mbv'][$k]['bar_code']=$v['bar_code'];
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
}
