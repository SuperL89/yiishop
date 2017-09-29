<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Banner;
use kartik\file\FileInput;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
$this->title = '创建banner';
$this->params['breadcrumbs'][] = ['label' => 'Banner', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="banner-form">

    <?php //$form = ActiveForm::begin(); ?>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
        ],
        'fieldConfig' => [
//            'template' => "<div class='row'><div class='col-lg-1 text-right text-fixed'>{label}</div><div class='col-lg-9'>{input}</div><div class='col-lg-2 errors'>{error}</div></div>",
        ]
    ]); ?>

    <?php // $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>
    
     <?= $form->field($upload, 'image_url')->widget(FileInput::classname(), [
    'options' => [
        'accept' => 'images/*',
        //'module' => 'Banner',
        'multiple' => true
    ],
    'pluginOptions' => [
        // 异步上传的接口地址设置
        'uploadUrl' => \yii\helpers\Url::to(['upload']),
        'uploadAsync' => true,
        // 异步上传需要携带的其他参数，比如商品id等,可选
        'uploadExtraData' => [
            'model' => 'Banner'
        ],
        // 需要预览的文件格式
        'previewFileType' => 'image',
        // 预览的文件
        'initialPreview' =>'',
        // 需要展示的图片设置，比如图片的宽度等
        'initialPreviewConfig' =>'',
        // 是否展示预览图
        'initialPreviewAsData' => true,
        // 最少上传的文件个数限制
        'minFileCount' => 1,
        // 最多上传的文件个数限制,需要配置`'multipe'=>true`才生效
        'maxFileCount' => 10,
        // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
        'showRemove' => false,
        // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
        'showUpload' => true,
        //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
        'showBrowse' => true,
        // 展示图片区域是否可点击选择多文件
        'browseOnZoneClick' => true,
        // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
        'fileActionSettings' => [
            // 设置具体图片的查看属性为false,默认为true
            'showZoom' => true,
            // 设置具体图片的上传属性为true,默认为true
            'showUpload' => true,
            // 设置具体图片的移除属性为true,默认为true
            'showRemove' => true,
        ],
        //网上很多地方都没详细说明回调触发事件，其实fileupload为上传成功后触发的，三个参数，主要是第二个，有formData，jqXHR以及response参数，上传成功后返回的ajax数据可以在response获取
        'pluginEvents' => [
            'fileuploaded' => "function (object,data){
				$('.field-goods-name').show().find('input').val(data.response.imageId);
			}",
            //错误的冗余机制
            'error' => "function (){
				alert('图片上传失败');
			}"
        ]
    ],
])?>
    
    <?php // $form->field($model, 'created_at')->textInput() ?>

    <?php // $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(Banner::allStatus()) ?>
    <div class="form-group">
       <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
