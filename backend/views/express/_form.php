<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Express;
/* @var $this yii\web\View */
/* @var $model common\models\Express */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Express::allStatus(),['prompt'=>'请选择状态']) ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
