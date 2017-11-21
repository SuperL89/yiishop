<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GoodMb */

$this->title = '更新商品报价: ' . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => '商家报价管理', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="good-mb-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'goodmodel' =>$goodmodel,
        'imagemodel' =>$imagemodel,
    ]) ?>

</div>
