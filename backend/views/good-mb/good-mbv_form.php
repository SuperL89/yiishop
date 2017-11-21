<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GoodMbv */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="good-mbv-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'mb_id')->textInput() ?>

    <?= $form->field($model, 'model_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock_num')->textInput() ?>

    <?= $form->field($model, 'bar_code')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'bar_code_status')->textInput() ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
