<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodCode */

$this->title = 'Create Good Code';
$this->params['breadcrumbs'][] = ['label' => 'Good Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
