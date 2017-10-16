<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class CategorysController extends ActiveController
{
    public $modelClass = 'api\models\Category';
    public $modelBrand = 'api\models\Brand';
    
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
       
        $modelClass = $this->modelClass;  
        $modelBrand = $this->modelBrand;
       
        $ishot = (int)Yii::$app->request->post("ishot");
        
        //查询所有分类
        $allCategorys = $modelClass::find()->select(['id','title','parentid'])->where(['status' => 0])->orderBy('order desc')->asArray()->all();
        //查询所有一级分类
        $categorys = $modelClass::find()->select(['id','title','parentid'])->where(['status' => 0, 'parentid' => null])->orderBy('order desc')->asArray()->all();
        if($ishot == 1){
            //查询一级分类下的热门品牌
            $categoryBrand = $modelBrand::find()->select(['id','title','image_url','cate_id'])->where(['status' => 0,'is_hot' => 1])->orderBy('order desc')->asArray()->all();
        }else{
            //查询一级分类下的所有品牌
            $categoryBrand = $modelBrand::find()->select(['id','title','image_url','cate_id'])->where(['status' => 0])->orderBy('order desc')->asArray()->all();
        }
        $child = array();
        //获取一级分类子分类
        $childTree = $this->actionCate($allCategorys, $child);
        //获取一级分类品牌
        $brand = $this->actionBrand($categorys, $categoryBrand);
        //合并子分类和品牌
        foreach ($childTree as $k => $v) {
            $childTree[$k]['brand'] = $brand[$k]['brand'];
        }
        
        $category['code'] = '200';
        $category['msg'] = '';
        $category['data'] = $childTree;
        return $category;
    }
    
    public function actionBrand(&$info, &$brands)
    {
        $child = array();
        if(!empty($info) && !empty($brands)){
            foreach ($info as $k => &$v) {
                if ($v['parentid'] == null) {
                    foreach ($brands as $bValue) {
                        if ($v['id'] == $bValue['cate_id']) {
                            $child[$v['id']]['brand'][] = $bValue;
                        }
                    }
                }
            }
        }
        $child = array_merge($child);
        
        return $child;
    }
    
    public function actionCate(&$info, $child, $pid = null)
    {
        $child = array();
        //当$info中的子类还没有被移光的时候
        if(!empty($info)){
            foreach ($info as $k => &$v) {
                //判断是否存在子类pid和返回的父类id相等的
                if($v['parentid'] == $pid){
                    //每次递归参数为当前的父类的id
                    $childs = $this->actionCate($info, $child, $v['id']);
                    //将$info中的键值移动到$child当中
                    $child[$v['id']] = $v;
                    //如果有子类,就将子类信息移动到$child当中
                    if ($childs) $child[$v['id']]['child'] = $childs;
                    //每次移动过去之后删除$info中当前的值
                    unset($info[$k]);
                }
            }
        }
        $child = array_merge($child);
        
        return $child;//返回生成的树形数组
    }
}
