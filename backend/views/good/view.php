<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\GoodMbv;

/* @var $this yii\web\View */
/* @var $model common\models\Good */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-view">

    <h1><?php // Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新商品', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'id',
            'good_num',
            'title',
            'description',
            [
                'attribute'=>'cate_id',
                'value'=>$model->cate->title,
            ],
            [
            'attribute'=>'image_url',
            'format' => ['html'],
            'value' => function($model){
                    $image_url='';
                    if($model->goodImages->image_url){
                        $images_arr = explode(',', $model->goodImages->image_url);
                        if($images_arr){
                            foreach ($images_arr as $key => $v){
                                $image_url .= "<img src=\"{$v}\" width=\"100\" height=\"100\" />&nbsp;";
                            }
                        }
                    }
                    return $image_url;
                }
            ],
            //'brand_id',
            [
                'attribute'=>'brand_id',
                'value'=>$model->brand->title,
            ],
            //'status',
            [
                'attribute'=>'status',
                'value'=>$model->StatusStr,
            ],
            //'is_reco',
            [
                'attribute'=>'is_reco',
                'value'=>$model->RecoStr,
            ],
            //'is_hot',
            [
                'attribute'=>'is_hot',
                'value'=>$model->HotStr,
            ],
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            //'user_id',
            'order',
        ],
    ]) ?>

</div>
