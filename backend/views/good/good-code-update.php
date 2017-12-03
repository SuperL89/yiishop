<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GoodCode */

$this->title = '更新条形码: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '条形码管理', 'url' => ['good-code', 'id' => $model->good_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="good-code-update">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('good-code_form', [
        'model' => $model,
    ]) ?>

</div>
