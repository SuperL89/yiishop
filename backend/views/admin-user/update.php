<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdminUser */

$this->title = '修改管理员用户: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '管理员用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '修改管理员用户 '];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]]
?>
<div class="admin-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
