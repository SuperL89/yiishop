<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserAddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '转运仓管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-address-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建转运仓库', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'attribute'=>'id',
            'headerOptions' => ['width' => '3%'],
            ],
            //'user_id',
            'name',
            //'country_id',
            //'state_id',
            //'city_id',
            'csc_name',
            'csc_name_en',
            'street',
            'phone',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
