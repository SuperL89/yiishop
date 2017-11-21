<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
            'attribute'=>'id',
            'headerOptions' => ['width' => '3%'],   
            ],
            'username',
            'nickname',
            //'sex',
            [
            'attribute'=>'sex',
            'value'=>'sexStr',
            'filter'=>User::allSex(),
            ],
            'money',
            [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>User::allStatus(),
            ],
            //'login_at',
            [
                'attribute'=>'login_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DateRangePicker::widget([
                    'name' => 'UserSearch[login_at]',
                    'options' => ['placeholder' => '','class' => 'form-control'],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('UserSearch')['login_at'],
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
            'login_ip',
            //'created_at',
            [
             'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DateRangePicker::widget([
                    'name' => 'UserSearch[created_at]',
                    'options' => ['placeholder' => '','class' => 'form-control'],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('UserSearch')['created_at'],
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

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {resetpwd}',
                'buttons'=>[
                    'resetpwd'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','重置密码'),
                            'aria-label'=>yii::t('yii','重置密码'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>',$url,$options);  
                    },
                ], 
            ],
        ],
    ]); ?>
</div>
