<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodMb */

$this->title = 'Create Good Mb';
$this->params['breadcrumbs'][] = ['label' => 'Good Mbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-mb-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
