<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '重置密码: ' . $username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-resetpwd">

    <h1><?php // Html::encode($this->title) ?></h1>

    <div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    
    
    <div class="form-group">
        <?= Html::submitButton('重置', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
	
</div>
