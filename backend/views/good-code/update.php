<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GoodCode */

$this->title = 'Update Good Code: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Good Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="good-code-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
