<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Brand;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品品牌';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建品牌', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'image_url:url',
            //'cate_id',
            [
            'attribute'=>'cate_id',
            'value'=>'cate.title',
            'filter'=>Category::find()
                    ->select(['title','id'])
                    ->where(['parentid'=>null])
                    ->orderBy('order')
                    ->indexBy('id')
                    ->column(),
            ],
            [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>Brand::allStatus(),
             ],
            'order',
            //'is_hot',
            [
            'attribute'=>'is_hot',
            'value'=>'hotStr',
            'filter'=>Brand::allHot(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
