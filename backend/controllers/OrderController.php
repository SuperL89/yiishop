<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use backend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\User;
use common\models\UserTradelog;

use yii\base\Exception;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionCreate()
//     {
//         $model = new Order();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('create', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 确认收货
     */
    public function actionConfirmReceipt($id)
    {
        
            //查询订单信息
            $orderData = $this->findModel($id);
            //验证订单状态
            switch ($orderData->status) {
                case 1: 
                    Yii::$app->getSession()->setFlash('error', '该订单为未支付状态,不可确认收货'); 
                    return $this->redirect(['index']);
                    break;
                case 2:
                    Yii::$app->getSession()->setFlash('error', '该订单为待发货状态,不可确认收货');
                    return $this->redirect(['index']);
                    break;
                case 4:
                    Yii::$app->getSession()->setFlash('error', '该订单为已完成状态,不可确认收货');
                    return $this->redirect(['index']);
                    break;
                case 5:
                    Yii::$app->getSession()->setFlash('error', '该订单为已出库状态,不可确认收货');
                    return $this->redirect(['index']);
                    break;
            }
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //订单信息
                $orderData->status = 4; //订单已完成
                $orderData->complete_at = time();
                //$orderData->save();
                if(!$orderData->save()){
                    $msg = array_values($orderData->getFirstErrors())[0];
                    print_r($msg);exit();
                }
                //修改商家用户金额
                $userData = new User();
                $userDatacount = $userData->updateAllCounters(array('money'=>$orderData->order_total_price), 'id=:id', array(':id' => $orderData->business_id)); //自动加减余额
                
                if ($userDatacount <= 0) {
                    Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
                    return $this->redirect(['index']);
                }
                
                //添加交易记录
                $usertradelog = new UserTradelog;
                $usertradelog->user_id = $orderData->business_id;
                $usertradelog->type = 1;
                $usertradelog->order_num = $orderData->order_num;
                $usertradelog->created_at = time();
                $usertradelog->money = $orderData->order_total_price;
                $usertradelog->save();
                
                $transaction->commit();
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                return $this->redirect(['index']);
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
                return $this->redirect(['index']);
            }
    }
    /**
     * 出库
     */
    public function actionTheLibrary($id)
    {
    
        //查询订单信息
        $orderData = $this->findModel($id);
        //验证订单状态
        switch ($orderData->status) {
            case 1:
                Yii::$app->getSession()->setFlash('error', '该订单为未支付状态,不可出库');
                return $this->redirect(['index']);
                break;
            case 2:
                Yii::$app->getSession()->setFlash('error', '该订单为待发货状态,不可出库');
                return $this->redirect(['index']);
                break;
            case 3:
                Yii::$app->getSession()->setFlash('error', '该订单为已发货状态,不可出库');
                return $this->redirect(['index']);
                break;
            case 5:
                Yii::$app->getSession()->setFlash('error', '该订单为已出库状态,不可出库');
                return $this->redirect(['index']);
                break;
        }
    
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            //订单信息
            $orderData->status = 5; //订单已出库
            $orderData->library_at = time();
            if(!$orderData->save()){
                $msg = array_values($orderData->getFirstErrors())[0];
                print_r($msg);exit();
            }
 
            $transaction->commit();
            Yii::$app->getSession()->setFlash('success', '操作成功！');
            return $this->redirect(['index']);
        }  catch(Exception $e) {
            # 回滚事务
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('error', '操作失败，请重试。');
            return $this->redirect(['index']);
        }
    }
}
