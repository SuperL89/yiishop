<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            // 'email:email',
             'role',
              //'status',
             [
                 'attribute'=>'status',
                 'value'=>'statusStr',
             ],
             //'login_at',
             [
                'attribute'=>'login_at',
                'format'=>['date','php:Y-m-d H:i:s'],
             ],
             'login_ip',
//              'created_at',
             [
               'attribute'=>'created_at', 
               'format'=>['date','php:Y-m-d H:i:s'],
             ],
             //'updated_at',
            [
                'attribute'=>'updated_at',
                'format'=>['date','php:Y-m-d H:i:s'],
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
                    'privilege'=>function($url,$model,$key)
                    {
                        $options =[
                            'title'=>Yii::t('yii','权限'),
                            'aria-label'=>yii::t('yii','权限'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-user"></span>',$url,$options);
                    },
                ], 
            ],
        ],
    ]); ?>
</div>
