<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\GoodMbv;
use common\models\GoodImage;
/* @var $this yii\web\View */
/* @var $model common\models\GoodMb */

$this->title = '报价商家：'.$model->user->username;
$this->params['breadcrumbs'][] = ['label' => '商家报价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-mb-view">

    <h1><?php // Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新商品报价信息', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'good_id',
            //'cate_id',
            [
            'label' => '商品标题',
            'value'=>$model->good->title,
            ],
            [
            'label' => '商品详细',
            'value'=>$model->good->description,
            ],
            [
            'label' => '商品图片',
            'format' => ['html'],
            'value' => function($model){
            $image_url='';
            $image_url_arr=GoodImage::find()->where(['good_id' =>$model->good->id])->one();
            //print_r($image_url_arr->image_url);exit();
            if($image_url_arr->image_url){
                $images_arr = explode(',', $image_url_arr->image_url);
                if($images_arr){
                    foreach ($images_arr as $key => $v){
                        $image_url .= "<img src=\"{$v}\" width=\"100\" height=\"100\" />&nbsp;";
                    }
                }
            }
            return $image_url;
            }
            ],
            [
            'attribute'=>'cate_id',
            'value'=>$goodmodel->cate->title,
            ],
            //'brand_id',
            [
            'attribute'=>'brand_id',
            'value'=>$goodmodel->brand->title,
            ],
            'id',
            //'user_id',
            [
            'attribute' => 'user_id',
            'value'=>$model->user->username,
            ],
            //'place_id',
            [
            'attribute' => 'place_id',
            'value'=>$model->place->name,
            ],
            //'freight_id',
            [
            'attribute' => 'freight_id',
            'value'=>$model->freight->title,
            ],
            //'status',
            [
            'attribute'=>'status',
            'label' => '商品报价状态',
            'value'=>$model->StatusStr,
            ],
            'created_at',
            'updated_at',
            [
            'attribute' => 'goodmbv',
            'label' => '商品属性',
            'format' => ['html'],
            'value'=> function($model){
                    $goodmbvs='';
                    $goodmbv_arr=GoodMbv::find()->where(['mb_id' =>$model->id])->all();
                    $goodmbvs .= "<p>型号 价格 库存 条形码</p>";
                    foreach ($goodmbv_arr as $k =>$v){
                        $goodmbvs .= "<p>".$v->model_text." ".$v->price." ".$v->stock_num." ".$v->bar_code."</p>";
                    }
                    
                    return $goodmbvs;
                }
            ],
        ],
    ]) ?>

</div>
