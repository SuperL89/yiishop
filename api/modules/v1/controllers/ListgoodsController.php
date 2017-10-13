<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Category;
use api\models\GoodClicks;
use api\models\GoodImage;
use yii\data\Pagination;

class ListgoodsController extends ActiveController
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

    public function actionIndex($ishot = '',$page = 1){
        $ishot = intval($ishot);
        $page = intval($page);
        //print_r($page);exit();
        if($ishot != 1 ){
            $ishot = '';
        }
        if($page <= 0 ){
            $page = 1;
        }
        
        $modelClass = $this->modelClass;
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $modelClass::find()->count(),
            'page' =>$page - 1,
        ]);
        if($ishot == 1){
            //获取热门商品列表
            $goods = $modelClass::find()
            ->select(['id','good_num','title','cate_id'])
            ->where(['status' => 0,'is_hot'=>$ishot])
            ->orderBy('order desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        }else{
            //获取全部商品列表
            $goods = $modelClass::find()
            ->select(['id','good_num','title','cate_id'])
            ->where(['status' => 0])
            ->orderBy('order desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        }
        
        if(!empty($goods)){
            foreach ($goods as $k => $v){
                //获取商品分类名
                $cate_name = Category::find()->where(['id' => $v['cate_id']])->select(['title'])->asArray()->one();
                $goods[$k]['cate_name']=$cate_name['title'];
                //获取商品图片
                $image_url = GoodImage::find()->select(['image_url'])->where(['good_id' => $v['id']])->asArray()->one();
                $goods[$k]['image_url']=$image_url['image_url'];
                //获取商品点击数
                $clicks = GoodClicks::find()->select(['clicks'])->where(['good_id' => $v['id']])->asArray()->one();
                $goods[$k]['clicks']=$clicks['clicks'];
            }
        }
        $good['code'] = '200';
        $good['msg'] = '';
        $good['data'] = $goods;
        return $good;
    }
}
