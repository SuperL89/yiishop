<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = '创建分类';
$this->params['breadcrumbs'][] = ['label' => '商品分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '二级分类', 'url' => ['category-index','id' => $cate_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>  -->

    <?= $this->render('category_form', [
        'model' => $model,
        'cate_id' => $cate_id
    ]) ?>

</div>
