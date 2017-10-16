<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Category;
use api\models\GoodClicks;
use api\models\GoodImage;
use api\models\Brand;
use api\models\GoodMb;
use api\models\GoodMbv;
use yii\data\Pagination;

class SearchgoodsController extends ActiveController
{
    public $modelClass = 'api\models\Good';
    
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

        $keyword = Yii::$app->request->post("keyword",'');
        $page = (int)Yii::$app->request->post("page", '1');
        
        if(empty($keyword) || $page <= 0){
            $good['code'] = '80000';
            $good['msg'] = '参数不合法或缺少参数';
            return $good;
        }
        
        $modelClass = $this->modelClass;
        //搜索模块
        //根据关键词搜索符合的商品id集合
        $good_title_ids = $modelClass::find()->select(['id'])->andFilterWhere(['like', 'title', $keyword ])->asArray()->all();
        $good_title_ids = $this->actionArr($good_title_ids, 'id');
        //根据商品编码搜索符合的商品id集合
        $good_goodnum_ids = $modelClass::find()->select(['id'])->andFilterWhere(['like', 'good_num', $keyword ])->asArray()->all();
        $good_goodnum_ids = $this->actionArr($good_goodnum_ids, 'id');
        //根据品牌搜索符合的商品id集合
        $brand=Brand::find()->select(['id'])->andFilterWhere(['like', 'title', $keyword ])->asArray()->one();
        if(!empty($brand)){//查询品牌是否存在
            $good_brand_ids = $modelClass::find()->select(['id'])->where(['brand_id' => $brand['id']])->asArray()->all();
            $good_brand_ids = $this->actionArr($good_brand_ids, 'id');
        }else{
            $good_brand_ids =array();
        }
        //根据条形码搜索符合的商品id集合
        $goodmbv=GoodMbv::find()->select(['mb_id'])->where(['bar_code' => $keyword , 'status' => 0 , 'bar_code_status' => 1])->asArray()->one();
        if(!empty($goodmbv)){//查询条形码是否存在
            $goodmb = GoodMb::find()->select(['good_id'])->where(['id' => $goodmbv['mb_id'],'status' => 0])->asArray()->one();
        }else{
            $good_barcode_ids =array();
        }
        if(!empty($goodmb)){
            $good_barcode_ids = $modelClass::find()->select(['id'])->where(['id' => $goodmb['good_id']])->asArray()->all();
            $good_barcode_ids = $this->actionArr($good_barcode_ids, 'id');
        }else{
            $good_barcode_ids =array();
        }
        //合并数组去重
        $good_ids = array_merge($good_title_ids,$good_goodnum_ids,$good_brand_ids,$good_barcode_ids);
        $good_ids = array_unique($good_ids);
        
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $modelClass::find()->count(),
            'page' =>$page - 1,
        ]);
        
        //获取全部商品列表
        $goods = $modelClass::find()
        ->select(['id','good_num','title','cate_id'])
        ->where(['status' => 0,'id'=>$good_ids])
        ->orderBy('order desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->asArray()
        ->all();
      
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
    //数组多维转一维 
    public function actionArr($array,$array_key){
        $arr = array();
        foreach($array as $value) {
            $arr[] = $value[$array_key];
        }
        return $arr;
    }
}
