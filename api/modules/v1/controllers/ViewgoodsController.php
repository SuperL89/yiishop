<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Brand;
use api\models\GoodImage;
use api\models\GoodClicks;


class ViewgoodsController extends ActiveController
{
    public $modelClass = 'api\models\Good';
    
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }
    
    public function actions()
    {
        $action =  parent::actions(); 
        unset($action['index'],$action['view'],$action['create'],$action['update'],$action['delete']); //所有动作删除
    }

    public function actionIndex(){
       $id = (int)Yii::$app->request->post("id", '0');
       
       if(!$id || $id <= 0){
            $good['code'] = '80000';
            $good['msg'] = '参数不合法或缺少参数';
            return $good;
        }
        $modelClass = $this->modelClass;
        $goods = $modelClass::find()
        ->select(['id','good_num','title','description','brand_id'])
        ->where(['status' => 0,'id' => $id])
        ->orderBy('order desc')
        ->asArray()
        ->one();
        if(!empty($goods)){
            //获取商品品牌名
            $brand_name = Brand::find()->where(['id' => $goods['brand_id']])->select(['title'])->asArray()->one();
            $goods['brand_name']=$brand_name['title'];
            //获取商品图片
            $image_url = GoodImage::find()->select(['image_url'])->where(['good_id' => $goods['id']])->asArray()->one();
            $goods['image_url']=$image_url['image_url'];
            //增加该商品的点击数
            $goodclicks=GoodClicks::find()->where(['good_id' => $goods['id']])->one();
            $goodclicks->clicks += 1;
            $goodclicks->save();
        }else{
            $good['code'] = '10002';
            $good['msg'] = '商品不存在或者已下架';
            return $good;
        }
        
        $good['code'] = '200';
        $good['msg'] = '';
        $good['data'] = $goods;
        return $good;
    }
}
