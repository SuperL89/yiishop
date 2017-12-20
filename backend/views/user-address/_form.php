<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Place;
/* @var $this yii\web\View */
/* @var $model common\models\UserAddress */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'country_id')->textInput() ?>

    <?php // $form->field($model, 'state_id')->textInput() ?>

    <?php // $form->field($model, 'city_id')->textInput() ?>
    <?= $form->field($model, 'place_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($model,'place_id_1', ArrayHelper::map(Place::getChildrenList(140), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    $.ajax({
                        type:"post",
                        dataType:"json",
                        url:"'.Yii::$app->urlManager->createUrl('/place-ajax/ajax-post-children-place').'",
                        data:{pid:$(this).val()},
                        success:function(msg){
                            $("#goodmb-place_id").html(msg.place);
                        }
                    });
                ',
            ])
            .
            Html::activeDropDownList($model,'place_id', ArrayHelper::map(Place::getChildrenList($model->place_id_1), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#goodmb-place_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); ?>

    <?php // $form->field($model, 'csc_name')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'csc_name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
