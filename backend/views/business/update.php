<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Business */

$this->title = '更新商家: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '商家管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="business-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
