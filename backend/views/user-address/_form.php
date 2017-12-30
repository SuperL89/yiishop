<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Place;
use api\models\UserAddress;
/* @var $this yii\web\View */
/* @var $model common\models\UserAddress */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    
    <?= $form->field($model, 'country_id')->hiddenInput(['value'=>'140'])->label(false); ?>
    
    <?= $form->field($model, 'state_id')->hiddenInput()->label(false); ?>
    
    <?= $form->field($model, 'csc_name')->hiddenInput()->label(false); ?>
    
    <?= $form->field($model, 'csc_name_en')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'city_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($model,'city_id_1', ArrayHelper::map(Place::getChildrenList(140, 1), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    var id = $(this).val();
                    $("#useraddress-state_id").val(id);
                    $.ajax({
                        type:"post",
                        dataType:"json",
                        url:"'.Yii::$app->urlManager->createUrl('/place-ajax/ajax-post-children-place').'",
                        data:{pid:$(this).val(),level:2},
                        success:function(msg){
                            $("#useraddress-city_id").html(msg.place);
                            if (id == 0) {
                                $("#useraddress-csc_name").val("");
                                $("#useraddress-csc_name_en").val("");
                            }
                        }
                    });
                ',
            ])
            .
            Html::activeDropDownList($model,'city_id', ArrayHelper::map(Place::getChildrenList($model->city_id_1, 2), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#useraddress-city_id").val($(this).val());
                    var pid = $("#useraddress-city_id_1").val();
                    $.ajax({
                        type:"post",
                        dataType:"json",
                        url:"'.Yii::$app->urlManager->createUrl('/place-ajax/ajax-post-select-place').'",
                        data:{id:$(this).val(),pid:pid},
                        success:function(msg){
                            $("#useraddress-csc_name").val(msg.name);
                            $("#useraddress-csc_name_en").val(msg.name_en);
                        }
                    });
                ',
            ]),
    ])->hiddenInput(); ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($model::allStatus(),['prompt'=>'请选择状态']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
