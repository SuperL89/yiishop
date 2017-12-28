<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserAddress */

$this->title = '编辑转运仓: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '转运仓管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-address-update">

    <h1><?php //Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
