<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserWithdrawalsapply;
use common\models\UserAccount;
use kartik\daterange\DateRangePicker;

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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
