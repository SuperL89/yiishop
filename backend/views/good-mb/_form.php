<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Good;
use common\models\Category;
use common\models\Brand;
use common\models\Place;
use common\models\GoodMb;
use common\models\Freight;
use common\models\UserAddress;

/* @var $this yii\web\View */
/* @var $model common\models\GoodMb */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="good-mb-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($goodmodel, 'good_num')->textInput() ?>

    <?= $form->field($goodmodel, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($goodmodel, 'description')->textarea(['rows'=>3]); ?>
    
    <?= $form->field($imagemodel, 'image_url')->widget('manks\FileInput', [
        'clientOptions' => [
            'pick' => [
                'multiple' => true,
            ]
        ],
    ]); ?>

    <?php // $form->field($model, 'cate_id')->textInput() ?>
    <?= $form->field($goodmodel, 'cate_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($goodmodel,'cate_id_1', ArrayHelper::map(Category::getChildrenList(0, 1), 'id', 'title'), [
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
            Html::activeDropDownList($goodmodel,'cate_id', ArrayHelper::map(Category::getChildrenList($goodmodel->cate_id_1, 2), 'id', 'title'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#good-cate_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); ?>

    <?php //$form->field($model, 'brand_id')->textInput() ?>
    <?= $form->field($goodmodel, 'brand_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($goodmodel,'brand_id', ArrayHelper::map(Brand::getBrandByCate($goodmodel->cate_id_1, 2), 'id', 'title'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#good-brand_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); ?>
	
    <?php // $form->field($model, 'user_id')->textInput() ?>

    <?php // $form->field($model, 'place_id')->textInput() ?>
    <?php /*$form->field($model, 'place_id', [
        'template'=>'{label}'.
            Html::activeDropDownList($model,'place_id_1', ArrayHelper::map(Place::getChildrenList(140, 1), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    $.ajax({
                        type:"post",
                        dataType:"json",
                        url:"'.Yii::$app->urlManager->createUrl('/place-ajax/ajax-post-children-place').'",
                        data:{pid:$(this).val(),level:2},
                        success:function(msg){
                            $("#goodmb-place_id").html(msg.place);
                        }
                    });
                ',
            ])
            .
            Html::activeDropDownList($model,'place_id', ArrayHelper::map(Place::getChildrenList($model->place_id_1, 2), 'id', 'name'), [
                'class' => 'form-control',
                'onchange' => '
                    $("#goodmb-place_id").val($(this).val());
                ',
            ]),
    ])->hiddenInput(); */?>

    <?php // $form->field($model, 'freight_id')->textInput() ?>
	<?php /*$form->field($model, 'freight_id')
             ->dropDownList(Freight::find()
             ->select(['title','id'])
             ->where(['user_id'=>$model->user_id])
             ->indexBy('id')
             ->column(),
             ['prompt'=>'请选择运费模版']
             );*/?>
    <?= $form->field($model, 'address_id')
             ->dropDownList(UserAddress::find()
             ->select(['name','id'])
             ->where(['status'=>0])
             ->indexBy('id')
             ->column(),
             ['prompt'=>'请选择仓库']
             );?>
    <?= $form->field($model, 'good_id')->textInput() ?>

    <?php // $form->field($model, 'cate_id')->textInput() ?>

    <?php // $form->field($model, 'brand_id')->textInput() ?>

    <?php // $form->field($model, 'status')->dropDownList(GoodMb::allStatus(),['prompt'=>'请选择状态']) ?>

    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
