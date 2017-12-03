<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GoodCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="good-code-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'good_id')->textInput() ?>
	<?= $form->field($model,'good_id')->textInput()->hiddenInput(['value'=>Yii::$app->request->get('good_id')])?>
	
    <?= $form->field($model, 'model_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bar_code')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
