<?php

namespace backend\controllers;

use Yii;
use common\models\Good;
use common\models\GoodMb;
use common\models\GoodMbv;
use backend\models\GoodSearch;
use common\models\GoodCode;
use backend\models\GoodCodesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GoodImage;
use common\components\Upload;
use yii\helpers\Json;
use common\models\GoodClicks;
use yii\base\Exception;

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
                    $goodclicks = new GoodClicks();
                    $goodclicks->good_id=$model->id;
                    $goodclicks->save();
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
     * Deletes an existing Good model.
     * If delete is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //修改商品删除状态
            $model = $this->findModel($id);
            $model->is_del = 1;
            $model->save();
            
            //修改商品报价删除状态
            $goodmb = GoodMb::find()->where(['good_id'=>$model->id])->all();
            $mbIds = array();
            foreach ($goodmb as $mb) {
                $mbIds[] = $mb->id;
                $mbEdit = array();
                $mbEdit['is_del'] = 1;
                GoodMb::updateAll($mbEdit, 'id=:id', array(':id' => $mb->id));
            }
            
            //修改商品属性删除状态
            $goodmbv = GoodMbv::find()->where(['in', 'mb_id', $mbIds])->all();
            foreach ($goodmbv as $mbv) {
                $mbvEdit = array();
                $mbvEdit['is_del'] = 1;
                GoodMbv::updateAll($mbvEdit, 'id=:id', array(':id' => $mbv->id));
            }
        
            $transaction->commit();
            Yii::$app->getSession()->setFlash('success', '操作成功！');
            return $this->redirect(['index']);
        } catch(Exception $e) {
            # 回滚事务
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('error', '操作失败，请重试。'.$e->getMessage());
            return $this->redirect(['index']);
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
    
    public function actionGoodCode($id)
    {
    
        $searchModel = new GoodCodesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('good-code', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'good_id' => $id,
        ]);
    }

    /**
     * Creates a new GoodCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateGoodCode()
    {
        $good_id = Yii::$app->request->get('good_id');
        $model = new GoodCode();
        $model->created_at = time();
        $model->updated_at = time();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['good-code', 'id' => $model->good_id]);
        } else {
            return $this->render('good-code-create', [
                'model' => $model,
                'good_id'=>$good_id,
            ]);
        }
    }

    /**
     * Updates an existing GoodCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateGoodCode($id)
    {
        $model = $this->findGoodCodeModel($id);
        $model->updated_at = time();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['good-code', 'id' => $model->good_id]);
        } else {
            return $this->render('good-code-update', [
                'model' => $model,
            ]);
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
    
    /**
    * Finds the GoodMbv model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return GoodMb the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
    protected function findGoodCodeModel($id)
    {
        if (($model = GoodCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
