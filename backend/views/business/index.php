<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Business;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BusinessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商家管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Business', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
            'attribute'=>'id',
            'headerOptions' => ['width' => '3%'],   
            ],
            [
                'attribute'=>'username',
                'label'=>'所属用户',
                'value'=>'user.username',
            ],
            //'image_url:url',
            'name',
            [
                'attribute'=>'city_name',
                'label'=>'城市',
                'value'=>'place.name',
            ],
            'address',
            //'cate_id',
            [
                'attribute'=>'cate_name',
                'label'=>'经营分类',
                'value'=>'cates',
            ],
            //'status',
            [
                'attribute'=>'status',
                'value'=>'statusStr',
                'filter'=>Business::allStatus(),
                'contentOptions' =>function($model)
                {
                    return ($model->status==0)?['class' => 'bg-danger']:[];
                }
            ],
            'score',
            //'score_updated_at',
            [
            'attribute'=>'score_updated_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'BusinessSearch[score_updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('BusinessSearch')['score_updated_at'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => '/',
                    ]
                ]
            ]),
            ],
            [
             'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DateRangePicker::widget([
                    'name' => 'BusinessSearch[created_at]',
                    'options' => ['placeholder' => '','class' => 'form-control'],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('BusinessSearch')['created_at'],
                     'convertFormat' => true,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '/',
                            ]
                        ]
               ]),
            ],
            //'updated_at',
            [
            'attribute'=>'updated_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'BusinessSearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('BusinessSearch')['updated_at'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => '/',
                    ]
                ]
            ]),
            ],

            
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}',
            ],
        ],
    ]); ?>
</div>
