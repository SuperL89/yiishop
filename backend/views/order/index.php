<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Order;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('导出订单', $params, ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'headerOptions' => ['width' => '3%', 'class'=>'text-center'],   
                'contentOptions' => ['class'=>'text-center'],
            ],
            'order_num',
            //'user_id',
            [
            'attribute'=>'username',
            'label'=>'购买用户',
            'value'=>'user.username',
            ],
            //'business_id',
            //'good_id',
            //'mb_id',
            //'mbv_id',
            //'user_address',
            //'pay_type',
            [
                'attribute'=>'pay_type',
                'label'=>'支付方式',
                'value'=>'paytypeStr',
                'filter'=>Order::allPaytype(),
            ],
            'good_price',
            'pay_num',
            'good_total_price',
            'order_fare',
            'order_total_price',
            //'express_name',
            //'express_num',
            //'status',
            [
                'attribute'=>'status',
                'value'=>'statusStr',
                'filter'=>Order::allStatus(),
            ],
            //'created_at',
            [
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'OrderSearch[created_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('OrderSearch')['created_at'],
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
            //'pay_at',
            [
            'attribute'=>'pay_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'OrderSearch[pay_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('OrderSearch')['pay_at'],
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
            //'deliver_at',
            [
            'attribute'=>'deliver_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'OrderSearch[deliver_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('OrderSearch')['deliver_at'],
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
            //'complete_at',
            [
            'attribute'=>'complete_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'OrderSearch[complete_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('OrderSearch')['complete_at'],
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
            //library_at
            [
            'attribute'=>'library_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'OrderSearch[library_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('OrderSearch')['library_at'],
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
            //'good_var',
            //'cancel_text',
            //'message',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{confirm-receipt}{the-library}',
                'buttons'=>[
                    'confirm-receipt'=>function($url,$model,$key)
                    {
                        if($model->status == 3){
                            $options =[
                                'title'=>Yii::t('yii','确认收货'),
                                'aria-label'=>yii::t('yii','确认收货'),
                                'data-confirm' => "确定要确认收货吗？",
                                'data-method' => 'post',
                                'data-pjax'=>'0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                        }
                    },
                    'the-library'=>function($url,$model,$key)
                    {
                        if($model->status == 4){
                            $options =[
                                'title'=>Yii::t('yii','出库'),
                                'aria-label'=>yii::t('yii','出库'),
                                'data-confirm' => "确定要出库吗？",
                                'data-method' => 'post',
                                'data-pjax'=>'0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-paste"></span>', $url, $options);
                        }
                    },
                ],
            ],
        
         ],
    ]); ?>
</div>
