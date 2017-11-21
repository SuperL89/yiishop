<?php

namespace backend\controllers;

use Yii;
use common\models\Good;
use common\models\GoodImage;
use common\models\GoodMb;
use backend\models\GoodMbSearch;
use common\models\GoodMbv;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\GoodMbvSearch;

/**
 * GoodMbController implements the CRUD actions for GoodMb model.
 */
class GoodMbController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all GoodMb models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodMbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodMb model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        $goodmodel = $this->findGoodModel($model->good_id);
        return $this->render('view', [
            'model' => $model,
            'goodmodel' =>$goodmodel,
        ]);
    }

    /**
     * Creates a new GoodMb model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionCreate()
//     {
//         $model = new GoodMb();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('create', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing GoodMb model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_at = time();
        $goodmodel = $this->findGoodModel($model->good_id);
        $goodmodel->updated_at = time();
        $imagemodel = $this->findGoodImageModel($goodmodel->id);
        
        //获取报价状态
        $goodmb = Yii::$app->request->post('GoodMb');
        $goodmb_status = $goodmb['status'];
        //print_r($goodmb_status);exit();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //如果商家报价状态为0 则同步商品状态为0
            if($goodmb_status == 0){
                $goodmodel->status = 0;
                //商品改为后台发布
                $goodmodel->user_id = 0;
            }
            //商品
            if ($goodmodel->load(Yii::$app->request->post()) && $goodmodel->save()) {
                //商品图片
                $GoodImage = Yii::$app->request->post('GoodImage');
                $image_url = $GoodImage['image_url'];
                $imagemodel->good_id = $goodmodel->id;
                $imagemodel->image_url = '';
                if (is_array($image_url) && $image_url) {
                    $imagemodel->image_url = implode(',', $image_url);
                }
                if ($imagemodel->save() && $imagemodel->validate()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }else {
                    return $this->render('update', [
                        'model' => $model,
                        'goodmodel' =>$goodmodel,
                        'imagemodel' =>$imagemodel,
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'goodmodel' =>$goodmodel,
                    'imagemodel' =>$imagemodel,
                ]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
                'goodmodel' =>$goodmodel,
                'imagemodel' =>$imagemodel,
            ]);
        }
    }

    /**
     * Deletes an existing GoodMb model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionDelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['index']);
//     }

    /**
     * Finds the GoodMb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodMb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodMb::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the Good model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodMb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGoodModel($id)
    {
        if (($model = Good::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Finds the Goodimages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodMb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGoodImageModel($id)
    {
        if (($model = GoodImage::find()->where(['good_id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Lists all GoodMbv models.
     * @return mixed
     */
    public function actionGoodMbv($id)
    {
        
        $searchModel = new GoodMbvSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('good-mbv', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Finds the GoodMbv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodMb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGoodMbvModel($id)
    {
        if (($model = GoodMbv::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGoodMbvUpdate($id)
    {
        $model = $this->findGoodMbvModel($id);
        
        $model->updated_at = time();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['good-mbv','id' => $model->mb_id]);
        } else {
            return $this->render('good-mbv-update', [
                'model' => $model,
            ]);
        }
    }
}
