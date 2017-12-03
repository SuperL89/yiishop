<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Business */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-view">

    <h1><?php // Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /* Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])*/ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'user_id',
            [
                'label'=>'用户名/联系方式',
                'value'=>$model->user->username,
            ],
            //'image_url:url',
            [
            'label' => '认证图片',
            'format' => ['html'],
            'value' => function($model){
            $image_url='';
            $image_url_arr= $model->image_url;
            //print_r($image_url_arr);exit();
            if($image_url_arr){
                $images_arr = explode(',', $image_url_arr);
                if($images_arr){
                    foreach ($images_arr as $key => $v){
                        $image_url .= "<img src=\"{$v}\" width=\"500\" height=\"300\" />&nbsp;";
                    }
                }
            }
            return $image_url;
            }
            ],
            'name',
            //'city_id',
            [
                'label'=>'经营分类',
                'value'=>$model->cates,
            ],
            'address',
            //'cate_id',
            [
                'label'=>'城市',
                'value'=>$model->place->name,
            ],
            //'status',
            [
                'attribute'=>'status',
                'value'=>$model->StatusStr,
            ],
            'score',
            [
                'attribute'=>'score_updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
//             'score_updated_at',
//             'created_at',
//             'updated_at',
        ],
    ]) ?>

</div>
