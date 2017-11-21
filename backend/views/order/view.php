<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->order_num;
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?php // Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            //'id',
            'order_num',
            [
                'label'=>'购买用户',
                'value'=>$model->user->username,
            ],
            
            //'business_id',
            [
                'label'=>'商家用户',
                'value'=>$model->business->username,
            ],
            [
                'label'=>'商品标题',
                'value'=>$model->goodvar->title,
            ],
            [
                'label'=>'商品图片',
                'value'=>$model->goodvar->good_image,
            ],
            [
                'label'=>'商品属性',
                'value'=>$model->goodvar->model_text,
            ],
            //'good_id',
            //'mb_id',
            //'mbv_id',
            
            //'user_address',
            [
            'label'=>'用户收货地址',
            'value'=>$model->useraddress,
            ],
            //'pay_type',
            [
                'attribute'=>'pay_type',
                'value'=>$model->paytypeStr,
            ],
            'good_price',
            'pay_num',
            'good_total_price',
            'order_fare',
            'order_total_price',
            'express_name',
            'express_num',
            //'status',
            [
                'attribute'=>'status',
                'value'=>$model->StatusStr,
            ],
//             'created_at',
//             'pay_at',
//             'deliver_at',
//             'complete_at',
            [
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
            'attribute'=>'pay_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
            'attribute'=>'deliver_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            ],
            [
            'attribute'=>'complete_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            ],
            //'good_var',
            'cancel_text',
            'message',
        ],
    ]) ?>

</div>
