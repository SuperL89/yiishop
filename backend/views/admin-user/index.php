<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AdminUser;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('新增管理员用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'nickname',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
             //'role',
              //'status',
             [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>AdminUser::allStatus(),
             ],
             //'login_at',
             [
                'attribute'=>'login_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                 //时间筛选
                 'filter' => DatePicker::widget([
                     'name' => 'AdminUserSearch[login_at]',
                     'options' => ['placeholder' => ''],
                     //注意，该方法更新的时候你需要指定value值
                     'value' => Yii::$app->request->get('AdminUserSearch')['login_at'],
                     'pluginOptions' => [
                         'autoclose' => true,
                         'format' => 'yyyy-mm-dd',
                         'todayHighlight' => true 
                     ]
                 ]),
             ],
             'login_ip',
//              'created_at',
            [
                'attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DatePicker::widget([
                    'name' => 'AdminUserSearch[created_at]',
                    'options' => ['placeholder' => ''],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('AdminUserSearch')['created_at'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]),
            ],
             //'updated_at',
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
                'headerOptions' => ['width' => '12%'],
                //时间筛选
                'filter' => DatePicker::widget([
                    'name' => 'AdminUserSearch[updated_at]',
                    'options' => ['placeholder' => ''],
                    //注意，该方法更新的时候你需要指定value值
                    'value' => Yii::$app->request->get('AdminUserSearch')['updated_at'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]),
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {resetpwd} {privilege}',
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
//                      'privilege'=>function($url,$model,$key)
//                      {
//                          $options =[
//                              'title'=>Yii::t('yii','权限'),
//                              'aria-label'=>yii::t('yii','权限'),
//                              'data-pjax'=>'0',
//                          ];
//                          return Html::a('<span class="glyphicon glyphicon-user"></span>',$url,$options);
//                      },
                ], 
            ],
        ],
    ]); ?>
</div>
