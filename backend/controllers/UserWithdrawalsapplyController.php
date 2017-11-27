<?php

namespace backend\controllers;

use Yii;
use common\models\UserWithdrawalsapply;
use backend\models\UserWithdrawalsapplySearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
/**
 * UserWithdrawalsapplyController implements the CRUD actions for UserWithdrawalsapply model.
 */
class UserWithdrawalsapplyController extends Controller
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
     * Lists all UserWithdrawalsapply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserWithdrawalsapplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserWithdrawalsapply model.
     * @param integer $id
     * @return mixed
     */
//     public function actionView($id)
//     {
//         return $this->render('view', [
//             'model' => $this->findModel($id),
//         ]);
//     }

    /**
     * Creates a new UserWithdrawalsapply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionCreate()
//     {
//         $model = new UserWithdrawalsapply();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('create', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing UserWithdrawalsapply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionUpdate($id)
//     {
//         $model = $this->findModel($id);

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('update', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Deletes an existing UserWithdrawalsapply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionDelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['index']);
//     }
        //审核通过
        public function actionStatusOk($id)
        {
            $model = $this->findModel($id);
            if($model->status != 0){
                Yii::$app->getSession()->setFlash('error', '非法操作！');
                return $this->redirect(['index']);
            }
            $model->status = 1;
            $model->updated_at=time();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
                return $this->redirect(['index']);
            }
        }
        //提现完成
        public function actionStatusSuccess($id)
        {
            $model = $this->findModel($id);
            if($model->status != 1){
                Yii::$app->getSession()->setFlash('error', '非法操作！');
                return $this->redirect(['index']);
            }
            $model->status = 2;
            $model->complete_at=time();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
                return $this->redirect(['index']);
            }
        }
        //提现拒绝
        public function actionStatusNo($id)
        {
            $model = $this->findModel($id);
            if($model->status != 0){
                Yii::$app->getSession()->setFlash('error', '非法操作！');
                return $this->redirect(['index']);
            }
            //事务处理
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //用户金额回流
                $userData = new User();
                $userDatacount = $userData->updateAllCounters(array('money'=>+ $model->money_w), 'id=:id', array(':id' => $model->user_id)); //自动加减余额
            
                $model->status = 3;
                $model->updated_at=time();
                $model->save();
                    
                $transaction->commit();
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                return $this->redirect(['index']);
            } catch (Exception $e) {
                # 回滚事务
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
                return $this->redirect(['index']);
            }
        }
    /**
     * Finds the UserWithdrawalsapply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserWithdrawalsapply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserWithdrawalsapply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
