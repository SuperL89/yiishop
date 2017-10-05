<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Brand;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'cate_id')->textInput() ?>
    <?= $form->field($model, 'cate_id')
             ->dropDownList(Category::find()
             ->select(['title','id'])
             ->where(['parentid'=>null])
             ->orderBy('order')
             ->indexBy('id')
             ->column(),
             ['prompt'=>'请选择分类']
             );?>

    <?= $form->field($model, 'status')->dropDownList(Brand::allStatus(),['prompt'=>'请选择状态']) ?>

    <?= $form->field($model, 'order')->textInput(['value'=>0]) ?>

    <?php //$form->field($model, 'is_hot')->textInput() ?>
    <?= $form->field($model, 'is_hot')->dropDownList(Brand::allHot(),['prompt'=>'请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
