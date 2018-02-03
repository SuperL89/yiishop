<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use api\models\Brand;
use api\models\GoodImage;
use api\models\GoodClicks;


class SharegoodsController extends ActiveController
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
        ->select(['id','title','description'])
        ->with([
            'goodImage'=> function ($query){
                $query->select(['*']);
            },
        ])
        ->where(['status' => 0,'is_del'=>0,'id' => $id])
        ->orderBy('order desc')
        ->asArray()
        ->one();
        
        if(!empty($goods)){
            $goods['title']=$goods['title'];
            $goods['description']=$goods['description'];
            $goods['image_url']=$goods['goodImage']['image_url'];
            //获取商品图片
            $goods['url']="http://wap.hexintrade.com/index.php?r=share%2Findex&id=".$id;
            
        }else{
            $good['code'] = '10002';
            $good['msg'] = '商品不存在或者已下架';
            return $good;
        }
        unset($goods['id']);
        unset($goods['goodImage']);
        $good['code'] = '200';
        $good['msg'] = '';
        $good['data'] = $goods;
        return $good;
    }
}
