<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserAddress */

$this->title = '添加转运仓';
$this->params['breadcrumbs'][] = ['label' => '转运仓管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-address-create">

    <h1><?php //Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
