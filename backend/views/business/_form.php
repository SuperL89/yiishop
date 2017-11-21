<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Business;
/* @var $this yii\web\View */
/* @var $model common\models\Business */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'user_id')->textInput() ?>

    <?php // $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'city_id')->textInput() ?>

    <?php // $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'cate_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Business::allStatus(),['prompt'=>'请选择状态']) ?>
	
    <?php // $form->field($model, 'score')->textInput() ?>

    <?php // $form->field($model, 'score_updated_at')->textInput() ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput()->hiddenInput(['value'=>time()])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
