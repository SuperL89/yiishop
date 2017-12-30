<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Good;
use common\models\Category;
use common\models\Brand;
/* @var $this yii\web\View */
/* @var $model common\models\Good */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="good-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'good_num')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows'=>3]); ?>
    
    <?= $form->field($imagemodel, 'image_url')->widget('manks\FileInput', [
        'clientOptions' => [
            'pick' => [
                'multiple' => true,
            ]
        ],
    ]); ?>

    <?php // $form->field($model, 'cate_id')->textInput() ?>
    <?= $form->field($model, 'cate_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($model,'cate_id_1', ArrayHelper::map(Category::getChildrenList(0, 1), 'id', 'title'), [
                'class' => 'form-control',
                'onchange' => '
                    $.ajax({
                        type:"post",
                        dataType:"json",
                        url:"'.Yii::$app->urlManager->createUrl('/category-ajax/ajax-post-children-cate').'",
                        data:{pid:$(this).val(),cateid:$(this).val(),level:2},
                        success:function(msg){
                            $("#good-cate_id").html(msg.cate);
                            $("#good-brand_id").html(msg.brand);
                        }
                    });
                ',
            ])
            .
            Html::activeDropDownList($model,'cate_id', ArrayHelper::map(Category::getChildrenList($model->cate_id_1, 2), 'id', 'title'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#good-cate_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); ?>

    <?php //$form->field($model, 'brand_id')->textInput() ?>
    <?= $form->field($model, 'brand_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($model,'brand_id', ArrayHelper::map(Brand::getBrandByCate($model->cate_id_1, 2), 'id', 'title'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#good-brand_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); ?>

    <?= $form->field($model, 'status')->dropDownList(Good::allStatus(),['prompt'=>'请选择状态']) ?>
    
    <?= $form->field($model, 'is_reco')->dropDownList(Good::allReco(),['prompt'=>'请选择']) ?>
    
    <?= $form->field($model, 'is_hot')->dropDownList(Good::allHot(),['prompt'=>'请选择']) ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <?php // $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'order')->textInput(['value'=>0]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
