<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Banner;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建Banner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            'image_url:url',
            'ad_url:url',
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DatePicker::widget([
                    'name' => 'BannerSearch[created_at]',
                    'options' => ['placeholder' => ''],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('BannerSearch')['created_at'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DatePicker::widget([
                    'name' => 'BannerSearch[updated_at]',
                    'options' => ['placeholder' => ''],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('BannerSearch')['updated_at'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>Banner::allStatus(),
             ],
            'order',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}',
            ],
        ],
    ]); ?>
</div>
