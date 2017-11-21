<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'business_id') ?>

    <?= $form->field($model, 'good_id') ?>

    <?php // echo $form->field($model, 'mb_id') ?>

    <?php // echo $form->field($model, 'mbv_id') ?>

    <?php // echo $form->field($model, 'user_address') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'good_price') ?>

    <?php // echo $form->field($model, 'pay_num') ?>

    <?php // echo $form->field($model, 'good_total_price') ?>

    <?php // echo $form->field($model, 'order_fare') ?>

    <?php // echo $form->field($model, 'order_total_price') ?>

    <?php // echo $form->field($model, 'express_name') ?>

    <?php // echo $form->field($model, 'express_num') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'pay_at') ?>

    <?php // echo $form->field($model, 'deliver_at') ?>

    <?php // echo $form->field($model, 'complete_at') ?>

    <?php // echo $form->field($model, 'good_var') ?>

    <?php // echo $form->field($model, 'cancel_text') ?>

    <?php // echo $form->field($model, 'message') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
