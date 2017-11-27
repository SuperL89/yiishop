<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserWithdrawalsapply;
use common\models\UserAccount;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserWithdrawalsapplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-withdrawalsapply-index">

    <h1><?php // Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
         <?php // Html::a('Create User Withdrawalsapply', ['create'], ['class' => 'btn btn-success']) ?>
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
            //'user_id',
             [
                'attribute'=>'username',
                'label'=>'所属用户',
                'value'=>'user.username',
             ],
            [
            'attribute'=>'type',
            'label'=>'账户类型',
            'value' => function ($model) {
                $type = [
                    '1' => '支付宝',
                    '2' => '银行卡',
                ];
                return $type[$model->userAccount['type']];
            },
            'headerOptions' => ['width' => '5%'],
            ],
            [
            'attribute'=>'account',
            'label'=>'账户',
            'value'=>'userAccount.account',
            ],
            [
            'attribute'=>'realname',
            'label'=>'姓名',
            'value'=>'userAccount.realname',
            ],
            [
            'attribute'=>'account_bank',
            'label'=>'开户行',
            'value'=>'userAccount.account_bank',
            ],
            'money_w',
            'commission_fee',
            'commission_money',
            'user_money',
            [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>UserWithdrawalsapply::allStatus(),
                 'contentOptions' =>function($model)
                 {
                    return ($model->status==0)?['class' => 'bg-danger']:[];
                 }
            ],
            //'created_at',
            [
            'attribute'=>'created_at',
            'format'=>['date','php:Y-m-d H:i:s'],
            'headerOptions' => ['width' => '12%'],
            //时间筛选
            'filter' => DateRangePicker::widget([
                'name' => 'UserWithdrawalsapplySearch[created_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('UserWithdrawalsapplySearch')['created_at'],
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
                'name' => 'UserWithdrawalsapplySearch[updated_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('UserWithdrawalsapplySearch')['updated_at'],
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
                'name' => 'UserWithdrawalsapplySearch[complete_at]',
                'options' => ['placeholder' => '','class' => 'form-control'],
                //注意，该方法更新的时候你需要指定value值
                'value' => Yii::$app->request->get('UserWithdrawalsapplySearch')['complete_at'],
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
            'template'=>'{status-ok}{status-no}{status-success}',
            'buttons'=>[
                'status-ok'=>function($url,$model,$key)
                {
                    if($model->status == 0){
                        $options =[
                            'title'=>Yii::t('yii','审核通过'),
                            'aria-label'=>yii::t('yii','审核通过'),
                            'data-confirm' => "确认要通过吗？",
                            'data-method' => 'post',
                            'data-pjax'=>'0',
                        ];
                        //return Html::a('<span class="glyphicon glyphicon-ok"></span>',$url,$options);
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                    }
                },
                'status-no'=>function($url,$model,$key)
                {
                    if($model->status == 0){
                        $options =[
                            'title'=>Yii::t('yii','审核拒绝'),
                            'aria-label'=>yii::t('yii','审核拒绝'),
                            'data-confirm' => "确认要拒绝吗？",
                            'data-method' => 'post',
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>',$url,$options);
                    }
                     
                },
                'status-success'=>function($url,$model,$key)
                {
                    if($model->status == 1){
                        $options =[
                            'title'=>Yii::t('yii','提现完成'),
                            'aria-label'=>yii::t('yii','提现完成'),
                            'data-confirm' => "确认要完成吗？",
                            'data-method' => 'post',
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-check"></span>',$url,$options);
                    }
                     
                },
            ],
            ],
        ],
    ]); ?>
</div>
