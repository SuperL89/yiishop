<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Good */

$this->title = '更新商品: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="good-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'imagemodel' => $imagemodel,
    ]) ?>

</div>
