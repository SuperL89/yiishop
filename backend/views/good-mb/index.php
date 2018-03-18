<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\GoodMb;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\GoodMbSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商家报价管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-mb-index">

    <h1><?php //Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Good Mb', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            //'good_id',
            [
            'attribute'=>'good_id',
            'headerOptions' => ['width' => '3%'],
            ],
            //'good.title',
            [
            'attribute'=>'good_title',
            'label'=>'商品标题',
            'value'=>'good.title',
            ],
//             [
//             'attribute'=>'good_title',
//             'value' => function (){
//                 $tmpStr = strip_tags(good.title);
//                 $tmpLen = mb_strlen($tmpStr);
//                 return mb_substr($tmpStr,0,20,'utf-8').(($tmpLen>20)?'...':'');
//             }
//             ],
            [
            'attribute'=>'id',
            'headerOptions' => ['width' => '5%'],   
            ],
            //'user_id',
            [
            'attribute'=>'username',
            'label'=>'商家用户',
            'value'=>'user.username',
            ],
            //'place_id',
//             [
//             'attribute'=>'city_name',
//             'label'=>'发货地',
//             'value'=>'place.name',
//             ],
//             //'freight_id',
// //             [
// //             'attribute'=>'freight_id',
// //             'value' => 'freight.title',
// //             ],            
//             [
//             'attribute'=>'freight_name',
//             'label'=>'运费模版',
//             'value'=>'freight.title',
//             ],
            [
                'attribute'=>'address_name',
                'label'=>'仓库',
                'value'=>'address.name',
            ],
            // 'cate_id',
            // 'brand_id',
            //'status',
           [
                'attribute'=>'status',
                'value'=>'statusStr',
                'filter'=>GoodMb::allStatus(),
                'contentOptions' =>function($model)
                {
                    return ($model->status==1)?['class' => 'bg-danger']:[];
                }
            ],
            [
                'attribute'=>'mb_status',
                'value'=>'statusUpDwStr',
                'filter'=>GoodMb::allStatusUpDw(),
            ],
            //'created_at',
            [
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'GoodMbSearch[created_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodMbSearch')['created_at'],
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
                'name' => 'GoodMbSearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodMbSearch')['updated_at'],
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
                'template'=>'{view}{update}{good-mbv}{delete}',
                'buttons'=>[
                'good-mbv'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','查看商品属性'),
                            'aria-label'=>yii::t('yii','查看商品属性'),
                            'data-pjax'=>'0',
                        ];
                        //return Html::a('<span class="glyphicon glyphicon-object-align-horizontal"></span><span class="label label-danger">9</span>',$url,$options);
                        return Html::a('<span class="glyphicon glyphicon-object-align-horizontal"></span>',$url,$options);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<style type="text/css">
.table td {position: relative;}
.label {
    position: absolute;
    top: 1px;
    right: 7px;
    text-align: center;
    font-size: 9px;
    padding: 2px 3px;
    line-height: .9;
}
</style>
