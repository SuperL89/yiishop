<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodCode */

$this->title = '创建条形码';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '条形码管理', 'url' => ['good-code','id' => $good_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-code-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('good-code_form', [
        'model' => $model,
    ]) ?>

</div>
