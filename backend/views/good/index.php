<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Good;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\GoodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增商品', ['create'], ['class' => 'btn btn-success']) ?>
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
            'good_num',
            //'title',
            [
            'attribute'=>'title',
            'value' => function ($model){
                $tmpStr = strip_tags($model->title);
                $tmpLen = mb_strlen($tmpStr);
                return mb_substr($tmpStr,0,20,'utf-8').(($tmpLen>20)?'...':'');
            }
            ],
            //'description',
            //'cate_id',
            [
            'attribute'=>'cate_name',
            'label'=>'分类名',
            'value'=>'cate.title',
            'headerOptions' => ['width' => '5%'],
            ],
            //'brand_id',
            [
            'attribute'=>'brand_name',
            'label'=>'品牌名',
            'value'=>'brand.title',
            'headerOptions' => ['width' => '5%'],
            ],
            //'status',
            [
            'attribute'=>'status',
            'value'=>'statusStr',
            'filter'=>Good::allStatus(),
            ],
            //'is_reco',
            [
            'attribute'=>'is_reco',
            'value'=>'recoStr',
            'filter'=>Good::allReco(),
            ],
            //'is_hot',
            [
            'attribute'=>'is_hot',
            'value'=>'hotStr',
            'filter'=>Good::allHot(),
            ],
            //'created_at',
            /*[
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'GoodSearch[created_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodSearch')['created_at'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => '/',
                    ]
                ]
            ]),
            ],*/
            //'updated_at',
            /*[
            'attribute'=>'updated_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'GoodSearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('GoodSearch')['updated_at'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'locale' => [
                        'format' => 'Y-m-d',
                        'separator' => '/',
                    ]
                ]
            ]),
            ],*/
            //'user_id',
//             [
//             'attribute'=>'username',
//             'label'=>'购买用户',
//             'value'=>'user.username',
//             ],
            'order',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{good-code}{delete}',
                'buttons'=>[
                    'good-code'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','查看商品条形码库'),
                            'aria-label'=>yii::t('yii','查看商品条形码库'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>',$url,$options);
                },
                ],
            ],
        ],
    ]); ?>
</div>
