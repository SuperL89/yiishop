<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */

$this->title = '查看管理员用户:'.$model->username;
$this->params['breadcrumbs'][] = ['label' => '管理员用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
//             'id',
            'username',
            'nickname', 
//             'auth_key',
//             'password_hash',
//             'password_reset_token',
            'email:email',
            //'role',
            //'status',
            [
                'attribute'=>'status',
                'value'=>$model->StatusStr,
            ],
            //'login_at',
            [
                'attribute'=>'login_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            'login_ip',
            //'created_at',
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
            //'updated_at',
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>
