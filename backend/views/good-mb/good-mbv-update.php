<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GoodMbv */

$this->title = '更新商品属性: ' . $model->model_text;
$this->params['breadcrumbs'][] = ['label' => '商家报价管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '商品属性管理', 'url' => ['good-mbv','id' => $model->mb_id]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="good-mbv-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('good-mbv_form', [
        'model' => $model,
    ]) ?>

</div>
