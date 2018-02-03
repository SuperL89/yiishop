<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Good;

/**
 * Share controller
 */
class ShareController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = false;
    
        $id = (int)Yii::$app->request->get("id", '0');
        $model = Good::find()
        ->select(['id','good_num','title','description','brand_id'])
        ->with([
            'goodImages'=> function ($query){
                $query->select(['*']);
            },
            'brand'=> function ($query){
                $query->select(['*']);
            },
            'goodClicks'=> function ($query){
                $query->select(['*']);
            },
        ])
        ->where(['status' => 0,'is_del'=>0,'id' => $id])
        ->one();
        //print_r($goodImages);exit();
        if($model){
            //图片处理
            $goodImages = explode(',',$model->goodImages->image_url);
            return $this->render('index', ['model' => $model,'goodImages'=>$goodImages]);
        }else{
            return $this->render('null');
        }
        
    }
}
