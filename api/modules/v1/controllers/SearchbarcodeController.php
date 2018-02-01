<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Brand;
use api\models\GoodImage;
use api\models\GoodClicks;
use api\models\GoodMb;
use api\models\GoodMbv;

class SearchbarcodeController extends ActiveController
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
        $code = (int)Yii::$app->request->post("code", '0');
         
        if(!$code || $code <= 0){
            $good['code'] = '80000';
            $good['msg'] = '参数不合法或缺少参数';
            return $good;
        }
        
        $modelClass = $this->modelClass;
        //根据条形码搜索符合的商品id集合
        $goodmbv=GoodMbv::find()->select(['mb_id'])->where(['bar_code' => $code , 'status' => 0 , 'bar_code_status' => 1])->asArray()->one();
        if(!empty($goodmbv)){//查询条形码是否存在
            $goodmb = GoodMb::find()->select(['good_id'])->where(['id' => $goodmbv['mb_id'],'status' => 0])->asArray()->one();
        }else{
            $id =array();
        }
        if(!empty($goodmb)){
            $id = $modelClass::find()->select(['id'])->where(['id' => $goodmb['good_id'],'status' => 0,])->asArray()->one();
        }else{
            $id =array();
        }
        
        $goods = $modelClass::find()
        ->select(['id','good_num','title','description','brand_id'])
        ->where(['id' => $id,'status' => 0,'is_del'=>0])
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
            $good['msg'] = '商品不存在';
            return $good;
        }
        
        $good['code'] = '200';
        $good['msg'] = '';
        $good['data'] = $goods;
        return $good;
    }
}
