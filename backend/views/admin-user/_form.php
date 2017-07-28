<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AdminUser;

/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?> 

    <?php // $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(AdminUser::allStatus(),['prompt'=>'请选择状态']) ?>

    <?php // $form->field($model, 'login_at')->textInput() ?>

    <?php // $form->field($model, 'login_ip')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php //$form->field($model, 'updated_at')->textInput() ?>

	<?= $form->field($model, 'updated_at')->textInput()->hiddenInput(['value'=>time()])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
