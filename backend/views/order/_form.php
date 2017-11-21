<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'business_id')->textInput() ?>

    <?= $form->field($model, 'good_id')->textInput() ?>

    <?= $form->field($model, 'mb_id')->textInput() ?>

    <?= $form->field($model, 'mbv_id')->textInput() ?>

    <?= $form->field($model, 'user_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'good_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pay_num')->textInput() ?>

    <?= $form->field($model, 'good_total_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_fare')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_total_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'express_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'express_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'pay_at')->textInput() ?>

    <?= $form->field($model, 'deliver_at')->textInput() ?>

    <?= $form->field($model, 'complete_at')->textInput() ?>

    <?= $form->field($model, 'good_var')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cancel_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
