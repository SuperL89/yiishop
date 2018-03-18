<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\GoodMbv;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\GoodMbvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品属性管理';
$this->params['breadcrumbs'][] = ['label' => '商家报价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-mbv-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Good Mbv', ['create'], ['class' => 'btn btn-success']) ?>
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
            //'mb_id',
            'model_text',
            'price',
            'stock_num',
            'bar_code',
            // 'bar_code_status',
            [
                'attribute'=>'status',
                'value'=>'statusStr',
                'filter'=>Goodmbv::allStatus(),
                'contentOptions' =>function($model)
                {
                    return ($model->status==2)?['class' => 'bg-danger']:[];
                }
            ],
            //'created_at',
            [
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'GoodMbvSearch[created_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodMbvSearch')['created_at'],
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
                'name' => 'GoodMbvSearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodMbvSearch')['updated_at'],
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
                'template'=>'{status-ok}{good-mbv-update}{good-mbv-delete}',
                'buttons'=>[
                'good-mbv-update'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','修改商品属性'),
                            'aria-label'=>yii::t('yii','修改商品属性'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url,$options);
                    },
                    'status-ok'=>function($url,$model,$key)
                    {
                        if($model->status == 2){
                            $options =[
                                'title'=>Yii::t('yii','审核通过'),
                                'aria-label'=>yii::t('yii','审核通过'),
                                'data-confirm' => "确认要通过吗？",
                                'data-method' => 'post',
                                'data-pjax'=>'0',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                        }
                    },
                    'good-mbv-delete'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','删除商品属性'),
                            'aria-label'=>yii::t('yii','删除商品属性'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url,$options);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
