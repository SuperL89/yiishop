<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Express */

$this->title = '创建快递公司';
$this->params['breadcrumbs'][] = ['label' => '快递管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="express-create">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
