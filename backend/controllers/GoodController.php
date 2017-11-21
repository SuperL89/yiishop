<?php

namespace backend\controllers;

use Yii;
use common\models\Good;
use backend\models\GoodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GoodImage;
use common\components\Upload;
use yii\helpers\Json;

/**
 * GoodController implements the CRUD actions for Good model.
 */
class GoodController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Good models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Good model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Good model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Good();
        $imagemodel = new GoodImage();
        $model->created_at = time();
        $model->updated_at = time();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           $GoodImage = Yii::$app->request->post('GoodImage');
           $image_url = $GoodImage['image_url'];
           $imagemodel->good_id = $model->id;
           $imagemodel->image_url = '';
           if (is_array($image_url) && $image_url) {
               $imagemodel->image_url = implode(',', $image_url);
           }
           if ($imagemodel && $imagemodel->validate()) {
                if ($imagemodel->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'imagemodel' => $imagemodel,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'imagemodel' => $imagemodel,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'imagemodel' => $imagemodel,
            ]);
        }
    }
    
    /**
     * Updates an existing Good model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $imagemodel = GoodImage::find()->where(['good_id' => $id])->one();
        $model->updated_at = time();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $GoodImage = Yii::$app->request->post('GoodImage');
            $image_url = $GoodImage['image_url'];
            $imagemodel->good_id = $model->id;
            $imagemodel->image_url = '';
            if (is_array($image_url) && $image_url) {
                $imagemodel->image_url = implode(',', $image_url);
            }
            if ($imagemodel && $imagemodel->validate()) {
                if ($imagemodel->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'imagemodel' => $imagemodel,
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'imagemodel' => $imagemodel,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'imagemodel' => $imagemodel,
            ]);
        }
    }
    
    /**
     * 上传图片
     */
    public function actionUpload()
    {
        try {
            $model = new Upload();
            $info = $model->upImageToQny();
    
            $info && is_array($info) ?
            exit(Json::htmlEncode($info)) :
            exit(Json::htmlEncode([
                'code' => 1,
                'msg' => 'error'
            ]));
        } catch (\Exception $e) {
            exit(Json::htmlEncode([
                'code' => 1,
                'msg' => $e->getMessage()
            ]));
        }
    }

    /**
     * Deletes an existing Good model.
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
     * Finds the Good model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Good the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Good::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
