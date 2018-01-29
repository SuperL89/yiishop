<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'image_h')->widget('manks\FileInput', []); ?>
    <?= $form->field($model, 'nickname')->textInput() ?>
	<?= $form->field($model, 'sex')->radioList(['1'=>'男','0'=>'女']) ?>
    <?= $form->field($model, 'commission_fee')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(User::allStatus(),['prompt'=>'请选择状态']) ?>
	<?= $form->field($model, 'updated_at')->textInput()->hiddenInput(['value'=>time()])->label(false); ?>
    <div class="form-group">
       <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
