<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Express;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ExpressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '快递管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="express-index">

    <h1><?php //Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            [
                'attribute'=>'status',
                'value'=>'statusStr',
                'filter'=>Express::allStatus(),
//                 'contentOptions' =>function($model)
//                 {
//                     return ($model->status==0)?['class' => 'bg-danger']:[];
//                 }
            ],
            [
             'attribute'=>'created_at',
             'format'=>['date','php:Y-m-d H:i:s'],
             'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DateRangePicker::widget([
                    'name' => 'ExpressSearch[created_at]',
                    'options' => ['placeholder' => '','class' => 'form-control'],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('ExpressSearch')['created_at'],
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
                'name' => 'ExpressSearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('ExpressSearch')['updated_at'],
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

            //['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update}',
            ],
        ],
    ]); ?>
</div>
