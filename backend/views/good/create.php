<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Good */

$this->title = '创建商品';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'imagemodel' => $imagemodel,
    ]) ?>

</div>
