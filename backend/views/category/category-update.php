<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = '更新分类: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '商品分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '二级分类', 'url' => ['category-index','id' => $model->parentid]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>  -->

    <?= $this->render('category_form', [
        'model' => $model,
        'cate_id' => $model->parentid
    ]) ?>

</div>
