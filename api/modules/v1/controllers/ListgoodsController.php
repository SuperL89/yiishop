<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Category;
use api\models\GoodClicks;
use api\models\GoodImage;
use yii\data\Pagination;

class ListgoodsController extends ActiveController
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
        
        $ishot = (int)Yii::$app->request->post("ishot");
        $page = (int)Yii::$app->request->post("page", '1');
        
        if($page <= 0){
            $good['code'] = '80000';
            $good['msg'] = '参数不合法或缺少参数';
            return $good;
        }
       
        $modelClass = $this->modelClass;
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $modelClass::find()->where(['status' => 0,'is_del'=>0])->filterWhere(['status' => 0,'is_hot'=>$ishot])->count(),
            'page' =>$page - 1,
        ]);
        //获取商品列表
        $goods = $modelClass::find()
        ->select(['id','good_num','title','cate_id','brand_id'])
        ->with([
            'goodImage'=> function ($query){
                $query->select(['*']);
            },
            'cate'=> function ($query){
                $query->select(['*']);
            },
            'brand'=> function ($query){
                $query->select(['*']);
            },
            'goodClicks'=> function ($query){
                $query->select(['*']);
            },
        ])
        ->where(['status' => 0,'is_del'=>0])
        ->filterWhere(['status' => 0,'is_del'=>0,'is_hot'=>$ishot])
        ->orderBy('order desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->asArray()
        ->all();
        if(!empty($goods)){
            foreach ($goods as $k => $v){
                //获取商品分类名
                $goods[$k]['cate_name']=$v['cate']['title'];
                //获取商品图片
                $goods[$k]['image_url']=$v['goodImage']['image_url'];
                //获取商品品牌
                $goods[$k]['brand_name']=$v['brand']['title'];
                //获取商品点击数
                if(!empty($v['goodClicks'])){
                    $goods[$k]['clicks']=$v['goodClicks']['clicks'];
                }else{
                    $goods[$k]['clicks']='0';
                }
                
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
