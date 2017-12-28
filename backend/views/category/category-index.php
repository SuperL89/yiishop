<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '二级分类';
$this->params['breadcrumbs'][] = ['label' => '商品分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建分类', ['category-create','cate_id'=>$cate_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'categoryParent.title',
                'filter' => Html::activeTextInput($searchModel, 'parent_title', [
                    'class' => 'form-control', 'id' => null
                ]),
                'label' => '父类名称',
            ],
            //'parentid',
            [
                 'attribute'=>'status',
                 'value'=>'statusStr',
                 'filter'=>Category::allStatus(),
            ],
            'order',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>' {category-update}{delete}',
                'buttons'=>[
                    'category-update'=>function($url,$model,$key)
                    {
                        $options = [
                            'title'=>Yii::t('yii','编辑二级分类'),
                            'aria-label'=>yii::t('yii','编辑二级分类'),
                            'data-pjax'=>'0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url,$options);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
