<?php

namespace api\modules\v1\controllers;

use Yii;
use Qiniu\Auth;
use api\models\LoginForm;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use api\models\User;
use yii\web\IdentityInterface;
use api\models\SignupForm;
use api\models\ResetpwdForm;
use api\models\SmsLog;
use api\models\updateProfileForm;
use api\models\ResetPasswordForm;
use api\models\ModifyUsernameForm;
use api\models\UpdateUsernameForm;
use api\models\UserAddress;
use api\models\CreateAddressForm;
use api\models\UpdateAddressForm;
use api\models\Place;
use api\models\Category;
use api\models\Business;
use api\models\Good;
use api\models\GoodMbv;
use api\models\Freight;
use api\models\FreightVar;
use api\models\FreightForm;
use yii\base\Exception;
use api\models\GoodOrderForm;
use api\models\GetGoodOrderForm;
use yii\helpers\VarDumper;
use api\models\Order;
use api\models\ConfirmReceiptForm;
use api\models\BusinessStar;
use api\models\BusinessDeliveryForm;
use api\models\Express;
use api\models\BusinessCancelForm;
use api\models\BusinessStarForm;
use api\models\SetpaypwdForm;
use api\models\ResetPaypwdForm;
use yii\data\Pagination;
use api\models\UserTradelog;
use api\models\PayTypeForm;
use api\models\PaymentOrderForm;
use api\models\GoodMb;
use api\models\Brand;
use api\models\BusinessCreateGoodForm;
use api\models\GoodImage;
use api\models\GoodClicks;
use api\models\UserAccount;
use api\models\UserWithdrawalsapply;
use api\models\GoodCode;
use api\models\BusinessUpdateGoodForm;
use api\models\AlipaymentOrderForm;
//支付宝
use common\components\alipay\aop\AopClient;
use common\components\alipay\aop\request\AlipayTradeAppPayRequest;

use common\components\JiGuangPush;
use api\models\Feedback;

class UserController extends ActiveController
{
    public $modelClass = 'api\models\User';
      
    public function behaviors() 
    {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className(),
                    'tokenParam' => 'token',
                    'optional' => [
                        'login',
                        'register',
                        'send-verifycode',
                        'resetpwd',
                        'get-place',
                        'get-category',
                        'get-express',
                        'qiniu-token',
                        'transfer-bin',
                        'ali-payment-notify-order',
                        'ceshi'
                    ],
                ] 
        ] );
    }
    public function actions()
    {
        $action =  parent::actions(); 
        unset($action['index'],$action['view'],$action['create'],$action['update'],$action['delete']); //所有动作删除
    }
    //极光测试
    public function actionCeshi(){
    
        try {
            $model = new JiGuangPush();
            $platform = "all";
            //$tag = array("7");
            $alias = array("25");
            //$notification_alert = "hello";
            $message = "测试测试测试策划四测试";
            $options = array("apns_production" => false);
    
            $model->_init($platform, /*$tag*/ $alias/*, $notification_alert*/, $message, $options);
            $info = $model->push();
    
            if ($info && is_array($info) && $info['code'] == 0) {
                return array('code' => 200, 'msg' => '');
            } else {
                return array('code' => 10001, 'msg' => $info['msg']);
            }
        } catch (\Exception $e) {
            return array('code' => 10001, 'msg' => $e->getMessage());
        }
    }
    
    /**
     * 登录
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        $model->setAttributes(Yii::$app->request->post());
        if ($user = $model->login()) {
            if ($user instanceof IdentityInterface) {
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data']['token'] = $user->api_token;
                return $data;
            } else {
                $data['code'] = '10001';
                $msg =array_values($model->getFirstErrors())[0];
                $data['msg'] = $msg;
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    
    
    /**
     * 注册
     */
    public function actionRegister()
    {
        $model = new SignupForm();
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if($user=$model->signup()){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data']['userid'] = $user->id;
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        } 
    }
    
    /*忘记密码*/
    public function actionResetpwd()
    {
        $model = new ResetpwdForm();
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
                if($model->resetpwd()){
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                }
            }else {
                $data['code'] = '10001';
                $msg =array_values($model->getFirstErrors())[0];
                $data['msg'] = $msg;
                return $data;
            } 
    }
    
    /**
     * 发送短信验证码
     */
    public function actionSendVerifycode()
    {
        $user_data = Yii::$app->request->post();
        $username = $user_data['username'];//获取用户手机号/用户名
        $usage = $user_data['usage'];//获取验证码用途
        if(empty($username)||empty($usage)){
            $good['code'] = '80000';
            $good['msg'] = '参数不合法或缺少参数';
            return $good;
        }
        if(!$usage){
            $data['code'] = '10003';
            $data['msg'] = '缺少验证码用途';
            return $data;
        }
        $sms = new SmsLog();
        $code = $sms->rand_string(6,1); //生成获取验证码
        $sms->to = $username;
        $sms->code = $code;
        $sms->usage = $usage;
        $sms->create_time = time();
        if ($sms->save()) {
            //发送验证码
            $phone = $sms->to;
            $code1 = $sms->code;
            //Yii::$app->smser->send($phone , '【合心意】您的验证码是'.$code1);
            Yii::$app->smser->send('+1'.$phone , '【hexinyi】Your verification code is '.$code1);
        }else{
                $data['code'] = '10004';
                $data['msg'] = '验证码发送失败';
                return $data;
        }
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data']['code']=" ";
        return $data;
    }
    /**
     * 获取用户个人信息
     */
    public function actionUserProfile()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data']['userid'] = $user->id;
        $data['data']['username'] = $user->username;
        $data['data']['nickname'] = $user->nickname;
        $data['data']['sex'] = $user->sex;
        $data['data']['image_h'] = $user->image_h;
        //查询用户是否为商家
        $status = Business::find()->where(['user_id' =>$user->id,'status'=>1])->one();
        $data['data']['business_status'] = isset($status->status)?$status->status:0;
        return $data;
        
    }
    /**
     * 用户个人信息修改
     */
    public function actionUpdateProfile()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new updateProfileForm();
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if ($user = $model->update($user->id)) {
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data']['userid'] = $user->id;
                $data['data']['username'] = $user->username;
                $data['data']['nickname'] = $user->nickname;
                $data['data']['sex'] = $user->sex;
                $data['data']['image_h'] = $user->image_h;
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }  
    }
    /**
     * 用户修改密码
     */
    public function actionResetPassword()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new ResetPasswordForm;
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if ($user = $model->resetPassword()) {
                $data['code'] = '200';
                $data['msg'] = '';
                //$data['data']['username'] = $user->username;
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            } 
        }else {
                $data['code'] = '10001';
                $msg =array_values($model->getFirstErrors())[0];
                $data['msg'] = $msg;
                return $data;
        }
    }
    /**
     * 修改用户名/手机号
     */
    public function actionModifyUsername()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new ModifyUsernameForm;
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {      
            $data['code'] = '200';
            $data['msg'] = '';
            return $data; 
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
        
        
    }
    /**
     * 更新用户名/手机号
     */
    public function actionUpdateUsername()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $model = new UpdateUsernameForm;
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if ($user = $model->update($user->id)) {
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 获得用户收货地址
     */
    public function actionReceivingAddress()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $address_id = isset($user_data['address_id']) ? $user_data['address_id'] : '';
        $address_arr = UserAddress::find()->select(['*'])->where(['user_id'=>$user->id,'status'=>[0,1]])->filterWhere(['user_id'=>$user->id,'status'=>[0,1],'id'=>$address_id])->asArray()->all();
        if($address_arr){
            foreach ($address_arr as $k => $v){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'][$k]['id'] = $v['id'];
                $data['data'][$k]['name'] = $v['name'];
                $data['data'][$k]['country_id'] = $v['country_id'];
                $data['data'][$k]['state_id'] = $v['state_id'];
                $data['data'][$k]['city_id'] = $v['city_id'];
                $data['data'][$k]['csc_name'] = $v['csc_name'];
                $data['data'][$k]['csc_name_en'] = $v['csc_name_en'];
                $data['data'][$k]['street'] = $v['street'];
                $data['data'][$k]['phone'] = $v['phone'];
                $data['data'][$k]['status'] = $v['status'];
            }
            return $data;
        }else{
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] =[];
            return $data;
        }
    }
    /**
     * 新增用户收货地址
     */
    public function actionCreateAddress()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new CreateAddressForm;
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if ($user = $model->createaddress($user->id)) {
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
        
    }
    /**
     * 修改/删除用户收货地址
     */
    public function actionUpdateAddress()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $model = new UpdateAddressForm;
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            if ($user = $model->updateaddress($user->id)) {
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 获取地区表
     */
    public function actionGetPlace()
    {
         $user_data = Yii::$app->request->post();
         $place_id = isset($user_data['place_id']) ? $user_data['place_id'] : 140;
         $place_arr = Place::find()
         ->select(['id','path','name_en','name','level','pid','code'])->where(['pid'=>$place_id])->andWhere(['like','path',',140,'])
         ->orderBy(['name_en' => SORT_ASC])
         ->asArray()
         ->all();
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $place_arr;
        return $data;
    }
    /**
     * 获取分类表品牌
     */
    public function actionGetCategory()
    {
        $user_data = Yii::$app->request->post();
        $category_id = isset($user_data['category_id']) ? $user_data['category_id'] : 0;
        $category_arr = Category::find()
        ->select(['id','title','parentid'])->where(['parentid'=>$category_id,'status'=> 0])->with([
            'brands'=> function ($query) {
                $query->select(['id','title','cate_id'])->where(['status'=> 0])->orderBy('order desc');
             }
        ])
        ->orderBy('order desc')
        ->asArray()
        ->all();
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $category_arr;
        return $data;
    }
    /**
     * 申请商家
     */
    public function actionApplyBusiness()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        if(empty($user_data['image_url'])){
            $data['code'] = '10001';
            $msg ='认证图片不能为空.';
            $data['msg'] = $msg;
            return $data;
        }
        $model = new Business();
        $model->setAttributes(Yii::$app->request->post());
        if($model->find()->select(['id'])->where(['status'=>[0,1],'user_id'=>$user->id])->one()){
            $data['code'] = '10001';
            $msg ='请勿重复提交申请';
            $data['msg'] = $msg;
            return $data;
        }
        if (Yii::$app->request->post() && $model->validate()) {
            $model->user_id = $user->id;
            $model->image_url = $user_data['image_url'];
            $model->name = $user_data['name'];
            $model->city_id = $user_data['city_id'];
            $model->address = $user_data['address'];
            $model->cate_id = $user_data['cate_id'];
            $model->score_updated_at = time();
            $model->created_at = time();
            $model->updated_at = time();
            if ($model->save()) {
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else{
                $data['code'] = '10001';
                $msg =array_values($model->getFirstErrors())[0];
                $data['msg'] = $msg;
                return $data;
            }
    }
    /**
     * 获取商家信息
     */
    public function actionBusinessProfile()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $business_arr = Business::find()->select(['*'])->where(['user_id' => $user->id,'status' => 1])->with([
            'user'=> function ($query){
                $query->select(['id','username']);
            },
            'city'=> function ($query){
                $query->select(['*']);
            },
        ])
        ->asArray()
        ->one();
        
        if (empty($business_arr)){
            $data['code'] = '10001';
            $data['msg'] = '无此商家信息或未通过审核';
            return $data;
        }
        
        $cate_id_arr = explode(',', $business_arr['cate_id']);
        //查询商家分类
        $category_arr = Category::find()->select(['id','title'])->where(['id'=>$cate_id_arr])
        ->asArray()
        ->all();
        
        $business['name']=$business_arr['name'];
        $business['phone']=$business_arr['user']['username'];
        //发货城市
        $business['city_id']=$business_arr['city']['id'];
        $business['city_name']=$business_arr['city']['name'];
        $business['city_name_en']=$business_arr['city']['name_en'];
        $business['address']=$business_arr['address'];
        $business['cate']=$category_arr;
        //$business['business_status'] = isset($business_arr['status'])?$business_arr['status']:0;

        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $business;
        return $data;   
    }
    /**
     * 更新商家信息
     */
    public function actionUpdateBusiness()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $model = new Business();
        $model->setAttributes(Yii::$app->request->post());
        if (Yii::$app->request->post() && $model->validate()) {
            $business=$model::find()->select(['id'])->where(['status'=>1,'user_id'=>$user->id])->one();
           //print_r($business->name);exit();
            if($business){
                $business->user_id = $user->id;
                //$business->image_url = $user_data['image_url'];
                $business->name = $user_data['name'];
                $business->city_id = $user_data['city_id'];
                $business->address = $user_data['address'];
                $business->cate_id = $user_data['cate_id'];
                $business->score_updated_at = time();
                $business->created_at = time();
                $business->updated_at = time();
                if ($business->save()) {
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                }else {
                    $data['code'] = '10001';
                    $msg ='操作失败1';
                    $data['msg'] = $msg;
                    return $data;
                }
            }else{
                $data['code'] = '10001';
                $msg ='操作失败2';
                $data['msg'] = $msg;
                return $data;
            }
        }else{
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    // {"Freight": {"title": "模版"},"FreightVar": ["place_id_arr:0,num:1,freight:1.00,numadd:2,freightadd:2.00,place_names:0", "place_id_arr:337,num:,freight:0.00,numadd:,freightadd:0.00,place_names:'纽约1 纽约2 纽约3'"]}
    /**
     * 新增商家运费模版
     */
    public function actionCreateFreight(){
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        if(empty($user_data['data'])){
            $data['code'] = '10001';
            $data['msg'] = '运费模版数据不能为空';
            return $data;
        }
        $freight_arr = json_decode($user_data['data'],true);
        //print_r($freight_arr->FreightVar->place_id_arr);exit();
        
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $freight = new Freight();
            $freight->user_id=$user->id;
            $freight->title=$freight_arr['Freight']['title'];
            $freight->status = 0;
            //print_r($freight_arr['FreightVar']);exit();
            if($freight->save())
            {
                $freightArr = array();
                foreach($freight_arr['FreightVar'] as $key => $value) {
                    if ($value) {
                        $val = explode(",", $value);
                        if ($val) {
                            $freightArr[$key]['freight_id'] = $freight->id;
                            foreach($val as $v) {
                                list($k, $vv) = explode(":", $v);
                                $freightArr[$key][$k] = $vv;
                            }
                        }
                    }
                }
                
                if ($freightArr) {
                    $connection = \Yii::$app->db;
                    //数据批量入库
                    $connection->createCommand()->batchInsert('{{%freight_var}}',['freight_id','place_id_arr','num','freight','numadd','freightadd','place_names'],$freightArr)->execute();
                }
                $transaction->commit();
                
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            } else {
                $transaction->rollback();
                
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }  catch(Exception $e) {
            # 回滚事务
            $transaction->rollback();
            
            $data['code'] = '10001';
            $data['msg'] = $e->getMessage();
            return $data;
        }     
    }
    /**
     * 更新商家运费模版
     */
    public function actionUpdateFreight(){
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        if(empty($user_data['data'])){
            $data['code'] = '10001';
            $data['msg'] = '运费模版数据不能为空';
            return $data;
        }
        $model = new FreightForm();
        $model->setAttributes(Yii::$app->request->post());
        
        $freight_id = isset($user_data['freight_id']) ? $user_data['freight_id'] : '';
        if (Yii::$app->request->post() && $model->validate()) {
            $freight = Freight::findOne($freight_id); 
            $freight_arr = json_decode($user_data['data'],true);
 
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                
                $freight->user_id=$user->id;
                $freight->title=$freight_arr['Freight']['title'];
                $freight->status = 0;
        
                if($freight->save())
                {
                    FreightVar::deleteAll(['freight_id'=>$freight->id]);
                    
                    $freightArr = array();
                    foreach($freight_arr['FreightVar'] as $key => $value) {
                        if ($value) {
                            $val = explode(",", $value);
                            if ($val) {
                                $freightArr[$key]['freight_id'] = $freight->id;
                                foreach($val as $v) {
                                    list($k, $vv) = explode(":", $v);
                                    $freightArr[$key][$k] = $vv;
                                }
                            }
                        }
                    }
                    if ($freightArr) {
                        $connection = \Yii::$app->db;
                        //数据批量入库
                        $connection->createCommand()->batchInsert('{{%freight_var}}',['freight_id','place_id_arr','num','freight','numadd','freightadd','place_names'],$freightArr)->execute();
                    }
                    $transaction->commit();
        
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                } else {
                    $transaction->rollback();
        
                    $data['code'] = '10001';
                    $msg ='操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
        
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        }else{
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 获得商家运费模版列表
     */
    public function actionReceivingFreight(){
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }

        $freight_id = isset($user_data['freight_id']) ? $user_data['freight_id'] : '';
        $freight_arr = Freight::find()->select(['*'])->where(['user_id'=>$user->id,'status' => 0])->with([
            'freightVars'=> function ($query) {
                $query->select('*');
            }
        ])
        ->asArray()
        ->all();
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] =$freight_arr;
        return $data;
    }
    /**
     * 删除商家运费模版 token 模版id
     */
    public function actionDelFreight(){
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $freight_id = isset($user_data['freight_id']) ? $user_data['freight_id'] : 0;
        //查询该模版是否在使用中
        $goodmb = GoodMb::find()->where(['user_id'=>$user->id,'freight_id' =>$freight_id ])->one();
        if($goodmb){
            $data['code'] = '10001';
            $data['msg'] = '此运费模版正在使用中，无法删除。请先去修改相关商品的中的运费模版。';
            return $data;
        }
        $freight = Freight::findOne($freight_id);
        if(empty($freight)){
            $data['code'] = '10001';
            $data['msg'] = '运费模版不存在';
            return $data;
        }
        $freight->status = 1;
        if($freight->save()){
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] ='';
            return $data;
        }else{
            $data['code'] = '10001';
            $data['msg'] = '操作失败';
            return $data;
        }
    }
    
    /**
     * 获得商品订单数据 入参  token  mbv_id num 
     */
    public function actionGoodOrder(){
    
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $address_id = isset($user_data['address_id']) ? $user_data['address_id'] : '';
        $model = new GoodOrderForm();
        $model->setAttributes($user_data);
        if($user_data && $model->validate()){
            //获取用户购买商品信息
            $good_arr = GoodMbv::find()->select(['id','mb_id','model_text','price','stock_num'])->where(['id'=>$user_data['mbv_id'],'status' => 0])->with([
                'goodMb'=> function ($query) {
                    $query->select(['id','user_id','good_id','freight_id','address_id'])->andWhere(['status' => 0])->with([
                        'good'=> function ($query){
                            $query->select(['id','title'])->andWhere(['status' => 0])->with([
                                'goodImage'=> function ($query){
                                    $query->select(['id','good_id','image_url']);
                                }
                            ]);
                        },
                        'address'=> function ($query){
                            $query->select(['id','name','city_id','state_id','csc_name','csc_name_en','street','phone'])->andWhere(['status' => 0]);
                        }
                    ]);
                }
            ])
            ->one();
            //print_r($good_arr);exit();
            //验证商品订单信息
            $validateGoodOrderResult = $this->validateGoodOrder($good_arr, $user, $user_data['num']);
            if ($validateGoodOrderResult) return $validateGoodOrderResult;
            //获取用户默认收货地址
            $address_arr = $good_arr->goodMb->address;
            //$address_arr = UserAddress::find()->select(['id','name','city_id','state_id','csc_name','csc_name_en','street','phone'])->where(['user_id'=>$user->id,'status'=>1])->filterWhere(['user_id'=>$user->id,'id'=>$address_id])->one();
            //获取商家运费模版
//             $freight_arr = Freight::find()->select(['*'])->where(['id'=>$good_arr->goodMb->freight_id,'user_id'=>$good_arr->goodMb->user_id])->with([
//                 'freightVars'=>function ($query){
//                     $query->select(['id','freight_id','place_id_arr','num','freight','numadd','freightadd']);
//                 }
//             ])
//             ->one();
            
            //获取商品图片(小图)
//             $goodImage = '';
//             $goodImageJson = isset($good_arr->goodMb->good->goodImage->image_url)?$good_arr->goodMb->good->goodImage->image_url:'';
// //             if ($goodImageJson) {
// //                 $goodImageAry = json_decode($goodImageJson);
// //                 $goodImageAry = $goodImageAry[0];
// //                 $goodImage = $goodImageAry->small;
// //             }
            //计算运费
            //$orderFare = $this->calculateOrderFare($freight_arr, $address_arr, $user_data['num']);
            
            $orderFare = 0;
            //商品信息
            $goodData = array(
                'goodmvb_id' => $good_arr->id,
                'good_name' => $good_arr->goodMb->good->title,
                'good_image' =>isset($good_arr->goodMb->good->goodImage->image_url)?$good_arr->goodMb->good->goodImage->image_url:'',
                'good_model' => $good_arr->model_text,
                'good_price' => $good_arr->price,
                'good_num' => $user_data['num'],
                'good_total_price' => bcmul($good_arr->price, $user_data['num'], 2),
                'order_fare' => $orderFare,
                'order_total_price' => bcadd(bcmul($good_arr->price, $user_data['num'], 2), $orderFare, 2)
            );
            //用户默认地址,没有就返回空
            $addressData = array();
            if ($address_arr) {
                $addressData['address_id'] = $address_arr->id;
                $addressData['name'] = $address_arr->name;
                $addressData['phone'] = $address_arr->phone;
                $addressData['address_cn'] = $address_arr->csc_name;
                $addressData['address_en'] = $address_arr->csc_name_en;
                $addressData['address_street'] = $address_arr->street;
            }
            //返回信息
            $returnData = array(
                'good' => $goodData,
                'address' => $addressData
            );
            
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = $returnData;
            return $data;
        } else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    
    }
    
    /**
     * 提交商品订单数据 入参  token  mbv_id num message
     */
    public function actionGetGoodOrder(){
    
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $model = new GetGoodOrderForm();
        $model->setAttributes($user_data);
        if($user_data && $model->validate()){
            //获取用户购买商品信息
            $good_arr = GoodMbv::find()->select(['id','mb_id','model_text','price','stock_num'])->where(['id'=>$user_data['mbv_id'],'status' => 0])->with([
                'goodMb'=> function ($query) {
                    $query->select(['id','user_id','good_id','freight_id','address_id'])->andWhere(['status' => 0])->with([
                        'good'=> function ($query){
                            $query->select(['id','title'])->andWhere(['status' => 0])->with([
                                'goodImage'=> function ($query){
                                        $query->select(['id','good_id','image_url']);
                                    }
                                ]);
                            },
                        'address'=> function ($query){
                            $query->select(['id','name','city_id','state_id','csc_name','csc_name_en','street','phone'])->andWhere(['status' => 0]);
                        }
                        ]);
                    }
                ])
                ->one();
                //验证商品订单信息
                $validateGoodOrderResult = $this->validateGoodOrder($good_arr, $user, $user_data['num']);
                if ($validateGoodOrderResult) return $validateGoodOrderResult;
                //获取用户收货地址
                $address_arr = $good_arr->goodMb->address;
                //$address_arr = UserAddress::find()->select(['id','name','city_id','state_id','csc_name','csc_name_en','street','phone'])->where(['user_id'=>$user->id,'id'=>$user_data['address_id']])->one();
                //验证收获地址
                //$validateOrderAddressResult = $this->validateOrderAddress($address_arr, $user);
                $validateOrderAddressResult = $this->validateOrderAddress($address_arr);
                if ($validateOrderAddressResult) return $validateOrderAddressResult;
                //获取商家运费模版
//                 $freight_arr = Freight::find()->select(['*'])->where(['id'=>$good_arr->goodMb->freight_id,'user_id'=>$good_arr->goodMb->user_id])->with([
//                     'freightVars'=>function ($query){
//                         $query->select(['id','freight_id','place_id_arr','num','freight','numadd','freightadd']);
//                     }
//                 ])
//                 ->one();
                //计算运费
                //$orderFare = $this->calculateOrderFare($freight_arr, $address_arr, $user_data['num']);
                
                $orderFare =0;
                
                //获取商品图片(小图)
//                 $goodImage = '';
//                 $goodImageJson = isset($good_arr->goodMb->good->goodImage->image_url)?$good_arr->goodMb->good->goodImage->image_url:'';
//                 if ($goodImageJson) {
//                     $goodImageAry = json_decode($goodImageJson);
//                     $goodImageAry = $goodImageAry[0];
//                     $goodImage = $goodImageAry->small;
//                 }
                //订单商品信息
                $goodData = array();
                $goodData['title'] = $good_arr->goodMb->good->title;
                $goodData['good_image'] = isset($good_arr->goodMb->good->goodImage->image_url)?$good_arr->goodMb->good->goodImage->image_url:'';
                $goodData['model_text'] = $good_arr->model_text;
                   
               
                //订单用户地址信息
                $addressData = array();
                if ($address_arr) {
                    $addressData['address_id'] = $address_arr->id;
                    $addressData['name'] = $address_arr->name;
                    $addressData['phone'] = $address_arr->phone;
                    $addressData['address_cn'] = $address_arr->csc_name;
                    $addressData['address_en'] = $address_arr->csc_name_en;
                    $addressData['address_street'] = $address_arr->street;
                }
                $order = new Order();
                $order->order_num = $this->orderNumber($user);
                $order->user_id = $user->id;
                $order->business_id = $good_arr->goodMb->user_id;
                $order->good_id = $good_arr->goodMb->good->id;
                $order->mb_id = $good_arr->goodMb->id;
                $order->mbv_id = $good_arr->id;
                $order->user_address = json_encode($addressData);
                $order->good_price = $good_arr->price;
                $order->pay_num = $user_data['num'];
                $order->good_total_price = bcmul($good_arr->price, $user_data['num'], 2);
                $order->order_fare = $orderFare;
                $order->order_total_price = bcadd(bcmul($good_arr->price, $user_data['num'], 2), $orderFare, 2);
                $order->created_at = time();
                $order->good_var = json_encode($goodData);
                $order->message = isset($user_data['message']) ? $user_data['message'] : '';
                if ($order->save()) {
                    $data['code'] = '200';
                    $data['msg'] = '';
                    $data['data']['order_id'] = $order->order_num;
                    return $data;
                } else {
                    $data['code'] = '10001';
                    $msg ='操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
        } else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    
    /**
     * 获得用户购买订单列表 入参  token 订单状态 order_status 1待支付 2待发货 3已发货 4已完成 5已出库   $page
     */
    public function actionGetUserOrder()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $orderStatus = isset($user_data['order_status']) && $user_data['order_status'] ? $user_data['order_status'] : '';
        $page = (int)Yii::$app->request->post("page", '1');
        if($page <= 0){
            $good['code'] = '10001';
            $good['msg'] = '分页参数错误';
            return $good;
        }
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => Order::find()->where(['user_id'=>$user->id])->filterWhere(['user_id'=>$user->id,'status'=>$orderStatus])->count(),
            'page' =>$page - 1,
        ]);
        //如果订单状态有值就验证
        if ($orderStatus) {
            if ( ! is_numeric($orderStatus)) {
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] = array();
                return $data;
            }
            if( ! in_array($orderStatus, array(1,2,3,4,5))){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] = array();
                return $data;
            }
        }

        $order_arr = Order::find()->select(['*'])->where(['user_id'=>$user->id])->filterWhere(['user_id'=>$user->id,'status'=>$orderStatus])->with([
            'business'=>function ($query){
                $query->select(['id','nickname']);
            }
            
        ])
        ->orderBy('created_at desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        
        //返回信息
        $orderInfo = array();
        if ($order_arr) {
            foreach ($order_arr as $key => $value) {
                //获取商品信息
                $goodInfo = array();
                if ($value->good_var) {
                    $goodInfo = json_decode($value->good_var);
                }
                //获取用户收货地址信息
                $addressInfo = array();
                if ($value->user_address) {
                    $addressInfo = json_decode($value->user_address);
                }
                $orderInfo[$key]['business_name'] = $value->business->nickname;
                $orderInfo[$key]['order_id'] = $value->order_num;
                $orderInfo[$key]['good_id'] = $value->good_id;
                $orderInfo[$key]['good_name'] = isset($goodInfo->title) ? $goodInfo->title : '';
                $orderInfo[$key]['good_image'] = isset($goodInfo->good_image) ? $goodInfo->good_image : '';
                $orderInfo[$key]['good_model'] = isset($goodInfo->model_text) ? $goodInfo->model_text : '';
                $orderInfo[$key]['good_num'] = $value->pay_num;
                $orderInfo[$key]['good_price'] = $value->good_price;
                $orderInfo[$key]['good_total_price'] = $value->good_total_price;
                $orderInfo[$key]['order_fare'] = $value->order_fare;
                $orderInfo[$key]['order_total_price'] = $value->order_total_price;
                $orderInfo[$key]['order_status'] = $value->status;
                $orderInfo[$key]['pay_type'] = $value->pay_type;
                $orderInfo[$key]['express_name'] = $value->express_name;
                $orderInfo[$key]['express_num'] = $value->express_num;
                $orderInfo[$key]['created_at'] = date('Y.m.d H:i', $value->created_at);
                $orderInfo[$key]['pay_at'] = isset($value->pay_at) ? date('Y.m.d H:i', $value->pay_at) : '';
                $orderInfo[$key]['deliver_at'] = isset($value->deliver_at) ? date('Y.m.d H:i', $value->deliver_at) : '';
                $orderInfo[$key]['complete_at'] = isset($value->complete_at) ? date('Y.m.d H:i', $value->complete_at): '';
                $orderInfo[$key]['message'] = isset($value->message) ? $value->message : '';
                $orderInfo[$key]['address'] = $addressInfo;
            }
        }
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $orderInfo;
        return $data;
    }
    /**
     * 获得商家销售订单列表 入参  token 订单状态 order_status 1待支付 2待发货 3已发货 4已完成 5已出库 page
     */
    public function actionGetBusinessOrder()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $orderStatus = isset($user_data['order_status']) && $user_data['order_status'] ? $user_data['order_status'] : '';
        $page = (int)Yii::$app->request->post("page", '1');
        if($page <= 0){
            $good['code'] = '10001';
            $good['msg'] = '分页参数错误';
            return $good;
        }
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => Order::find()->where(['business_id'=>$user->id])->filterWhere(['business_id'=>$user->id,'status'=>$orderStatus])->count(),
            'page' =>$page - 1,
        ]);
        //如果订单状态有值就验证
        if ($orderStatus) {
            if ( ! is_numeric($orderStatus)) {
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] = array();
                return $data;
            }
            if( ! in_array($orderStatus, array(1,2,3,4,5))){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] = array();
                return $data;
            }
        }
    
        $order_arr = Order::find()->select(['*'])->where(['business_id'=>$user->id])->filterWhere(['business_id'=>$user->id,'status'=>$orderStatus])
        ->orderBy('created_at desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
            //返回信息
        $orderInfo = array();
        if ($order_arr) {
            foreach ($order_arr as $key => $value) {
                //获取商品信息
                $goodInfo = array();
                if ($value->good_var) {
                    $goodInfo = json_decode($value->good_var);
                }
                //获取用户收货地址信息
                $addressInfo = array();
                if ($value->user_address) {
                    $addressInfo = json_decode($value->user_address);
                }
                $orderInfo[$key]['order_id'] = $value->order_num;
                $orderInfo[$key]['good_id'] = $value->good_id;
                $orderInfo[$key]['good_name'] = isset($goodInfo->title) ? $goodInfo->title : '';
                $orderInfo[$key]['good_image'] = isset($goodInfo->good_image) ? $goodInfo->good_image : '';
                $orderInfo[$key]['good_model'] = isset($goodInfo->model_text) ? $goodInfo->model_text : '';
                $orderInfo[$key]['good_num'] = $value->pay_num;
                $orderInfo[$key]['good_price'] = $value->good_price;
                $orderInfo[$key]['good_total_price'] = $value->good_total_price;
                $orderInfo[$key]['order_fare'] = $value->order_fare;
                $orderInfo[$key]['order_total_price'] = $value->order_total_price;
                $orderInfo[$key]['order_status'] = $value->status;
                $orderInfo[$key]['pay_type'] = $value->pay_type;
                $orderInfo[$key]['express_name'] = $value->express_name;
                $orderInfo[$key]['express_num'] = $value->express_num;
                $orderInfo[$key]['created_at'] = date('Y.m.d H:i', $value->created_at);
                $orderInfo[$key]['pay_at'] = isset($value->pay_at) ? date('Y.m.d H:i', $value->pay_at) : '';
                $orderInfo[$key]['deliver_at'] = isset($value->deliver_at) ? date('Y.m.d H:i', $value->deliver_at) : '';
                $orderInfo[$key]['complete_at'] = isset($value->complete_at) ? date('Y.m.d H:i', $value->complete_at): '';
                $orderInfo[$key]['message'] = isset($value->message) ? $value->message : '';
                $orderInfo[$key]['address'] = $addressInfo;
            }
        }

        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $orderInfo;
        return $data;
    }
    
    /**
     * 用户确认收货以及评价  token order_id business_star good_star
     */
    public function actionConfirmReceipt()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new ConfirmReceiptForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->user_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //验证订单状态
            switch ($order_arr->status) {
                case 1: return array('code' => '10001', 'msg' => '该订单为未支付状态,不可确认收货'); break;
                case 2: return array('code' => '10001', 'msg' => '该订单为待发货状态,不可确认收货'); break;
                case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可确认收货'); break;
                case 5: return array('code' => '10001', 'msg' => '该订单为已出库状态,不可确认收货'); break;
            }
            //计算评分
            //$businessScore = $this->calculateBusinessScore($user_data);
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //订单信息
                $orderData = Order::findOne($order_arr->id);
                $orderData->status = 4; //订单已完成
                $orderData->complete_at = time();
                $orderData->save();
                //评分信息
//                 $businessStarData = new BusinessStar;
//                 $businessStarData->user_id = $order_arr->user_id;
//                 $businessStarData->business_id = $order_arr->business_id;
//                 $businessStarData->order_id = $order_arr->id;
//                 $businessStarData->good_star = $user_data['good_star'];
//                 $businessStarData->business_star = $user_data['business_star'];
//                 $businessStarData->created_at = time();
//                 $businessStarData->save();
                
                //修改商家评分
//                 $businessData = new Business();
//                 $businessDatacount = $businessData->updateAllCounters(array('score'=>$businessScore), 'user_id=:user_id', array(':user_id' => $order_arr->business_id)); //自动加减评分
//                 if ($businessDatacount <= 0) {
//                     $data['code'] = '10001';
//                     $msg = '操作失败';
//                     $data['msg'] = $msg;
//                     return $data;
//                 }
                //修改商家用户金额
//                 $userData = User::findOne($order_arr->business_id);
//                 $userData->refresh();
//                 $userData->money = $userData->money + $order_arr->order_total_price;
//                 $userData->save();
                //修改商家用户金额
                $userData = new User();
                $userDatacount = $userData->updateAllCounters(array('money'=>$order_arr->order_total_price), 'id=:id', array(':id' => $order_arr->business_id)); //自动加减余额
                if ($userDatacount <= 0) {
                    $data['code'] = '10001';
                    $msg = '操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
                //添加交易记录
                $usertradelog = new UserTradelog;
                $usertradelog->user_id = $order_arr->business_id;
                $usertradelog->type = 1;
                $usertradelog->order_num = $order_arr->order_num;
                $usertradelog->created_at = time();
                $usertradelog->money = $order_arr->order_total_price;
                $usertradelog->save();
                
                $transaction->commit();
                
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
                
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    
    /**
     * 商家发货  token order_id express_id express_number
     */
    public function actionBusinessDelivery()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        
        $model = new BusinessDeliveryForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->business_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //验证订单状态
            switch ($order_arr->status) {
                case 1: return array('code' => '10001', 'msg' => '该订单为未支付状态,不可发货'); break;
                case 3: return array('code' => '10001', 'msg' => '该订单为已发货状态,不可发货'); break;
                case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可发货'); break;
                case 5: return array('code' => '10001', 'msg' => '该订单为已出库状态,不可发货'); break;
            }
            //查询快递公司信息
            $express_arr = Express::find()->select(['*'])->where(['id' => $user_data['express_id'],'status' => 0])->one();

            //订单信息
            $orderData = Order::findOne($order_arr->id);
            $orderData->status = 3; //订单已发货
            $orderData->deliver_at = time();
            $orderData->express_name = $express_arr->name;
            $orderData->express_num = $user_data['express_number'];
            if ($orderData->save()) {
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            } else {
                $data['code'] = '10001';
                $data['msg'] = '操作失败';
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    
    /**
     * 商家取消订单  token order_id cancel_msg
     */
//     public function actionBusinessCancel()
//     {
//         $user_data = Yii::$app->request->post();
//         $token = $user_data['token'];
//         $user = User::findIdentityByAccessToken($token);
//         //验证是否为商家用户
//         $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
//         if(!$business){
//             $data['code'] = '10001';
//             $data['msg'] = '不是商家用户或未通过商家审核';
//             return $data;
//         }
        
//         $model = new BusinessCancelForm();
//         $model->setAttributes($user_data);
//         if ($user_data && $model->validate()) {
//             //查询订单信息
//             $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
//             //验证订单是否是当前用户
//             if ($order_arr->business_id != $user->id) {
//                 return array('code' => '10001', 'msg' => '您无权限操作该订单');
//             }
//             //验证订单状态
//             switch ($order_arr->status) {
//                 case 2: return array('code' => '10001', 'msg' => '该订单为待发货状态,不可取消'); break;
//                 case 3: return array('code' => '10001', 'msg' => '该订单为已发货状态,不可取消'); break;
//                 case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可取消'); break;
//                 case 5: return array('code' => '10001', 'msg' => '该订单为已取消状态,不可取消'); break;
//             }
//             //订单信息
//             $orderData = Order::findOne($order_arr->id);
//             $orderData->status = 5; //订单已取消
//             $orderData->complete_at = time();
//             $orderData->cancel_text = $user_data['cancel_msg'];
//             if ($orderData->save()) {
//                 $data['code'] = '200';
//                 $data['msg'] = '';
//                 return $data;
//             } else {
//                 $data['code'] = '10001';
//                 $data['msg'] = '操作失败';
//                 return $data;
//             }
//         } else {
//             $data['code'] = '10001';
//             $msg = array_values($model->getFirstErrors())[0];
//             $data['msg'] = $msg;
//             return $data;
//         }
//     }
    
    /**
     * 查看商家评价  token order_id
     */
    public function actionGetBusinessStar()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $model = new BusinessStarForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->business_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //查看商品评价信息
            $businessStar = BusinessStar::find()->select(['*'])->where(['business_id' => $order_arr->business_id, 'order_id' => $order_arr->id])->one();
            //返回信息
            $businessStarInfo = array();
            if ($businessStar) {
                $businessStarInfo['good_star'] = $businessStar->good_star;
                $businessStarInfo['business_star'] = $businessStar->business_star;
            }
                
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = $businessStarInfo;
            return $data;
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 查看用户收支明细 token
     */
    public function actionUserTradelog()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $page = (int)Yii::$app->request->post("page", '1');
        if($page <= 0){
            $good['code'] = '10001';
            $good['msg'] = '分页参数错误';
            return $good;
        }
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => UserTradelog::find()->where(['user_id'=>$user->id])->count(),
            'page' =>$page - 1,
        ]);
        $tradelog_arr = UserTradelog::find()->where(['user_id' => $user->id])
        ->orderBy('created_at desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->asArray()
        ->all();
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $tradelog_arr;
        return $data; 
    }
    /**
     * 获取用户钱包 token
     */
    public function actionUserWallet()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $wallet_arr = array('image_h'=>$user->image_h,'money'=>$user->money);
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $wallet_arr;
        return $data;
    }
    /**
     * 支付设置 token
     */
    public function actionUserPaySet()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        if(empty($user->pay_pw)){
            //未设置
            $set['set'] = 0;
        }else{
            //已设置
            $set['set'] = 1;
        }
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $set;
        return $data;
    }
    /**
     * 支付密码设置/忘记支付密码 token verifycode  pay_pw pay_pw_repeat
     */
    public function actionUserPaypasswordSet()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $user_data['username'] = $user->username;
        
        $model = new SetpaypwdForm();
        $model->setAttributes($user_data);
        //print_r($user_data);exit();
        if ($user_data && $model->validate()) {
            if($model->setpaypwd()){
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 修改支付密码 token verifycode  pay_pw_old pay_pw
     */
    public function actionUserPaypasswordReset()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $user_data['username'] = $user->username;
        
        $model = new ResetPaypwdForm;
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            if ($model->ResetPaypwd()) {
                $data['code'] = '200';
                $data['msg'] = '';
                //$data['data'] = $user->pay_pw;
                return $data;
            }else {
                $data['code'] = '10001';
                $msg ='操作失败';
                $data['msg'] = $msg;
                return $data;
            }
        }else {
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 付款方式  token order_id
     */
    public function actionPayType()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new PayTypeForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->user_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //验证订单状态
            switch ($order_arr->status) {
                case 2: return array('code' => '10001', 'msg' => '该订单为待发货状态,不可支付'); break;
                case 3: return array('code' => '10001', 'msg' => '该订单为发货状态,不可支付'); break;
                case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可支付'); break;
                case 5: return array('code' => '10001', 'msg' => '该订单为已出库状态,不可支付'); break;
            }
            
            $paytype['money'] = $user->money;
            if($user->money >= $order_arr->order_total_price){
                //可以支付
                $paytype['pay_status'] = 1;
            }else{
                $paytype['pay_status'] = 0;
            }
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = $paytype;
            return $data;
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
        
    }
    
    /**
     * 用户余额支付订单  token order_id pay_pw
     */
    public function actionPaymentOrder()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $user_data['username'] = $user->username;
        
        $model = new PaymentOrderForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->user_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //验证订单状态
            switch ($order_arr->status) {
                case 2: return array('code' => '10001', 'msg' => '该订单为待发货状态,不可支付'); break;
                case 3: return array('code' => '10001', 'msg' => '该订单为发货状态,不可支付'); break;
                case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可支付'); break;
                case 5: return array('code' => '10001', 'msg' => '该订单为已出库状态,不可支付'); break;
            }
            //验证用户余额是否充足
            if($user->money <= $order_arr->order_total_price){
                return array('code' => '10001', 'msg' => '余额不足');
            }
            //验证库存是否充足
            $goodmbv = GoodMbv::findOne($order_arr->mbv_id);
            if ( ! $goodmbv) return array('code' => '10001', 'msg' => '商品信息不存在');
            $goodmbv->refresh();
            if($goodmbv->stock_num < $order_arr->pay_num){
                return array('code' => '10001', 'msg' => '库存不足');
            }
    
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //订单信息
                $orderData = Order::findOne($order_arr->id);
                $orderData->status = 2; //订单已支付
                $orderData->pay_at = time();
                $orderData->save();
                //修改用户金额
                $userData = new User();
                $userDatacount = $userData->updateAllCounters(array('money'=>-$order_arr->order_total_price), 'id=:id', array(':id' => $order_arr->user_id)); //自动加减余额
                if ($userDatacount <= 0) {
                    $data['code'] = '10001';
                    $msg = '操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
                //修改商品库存
                $goodmbv = new GoodMbv();
                $goodmbvcount = $goodmbv->updateAllCounters(array('stock_num'=>-$order_arr->pay_num), 'id=:id', array(':id' => $order_arr->mbv_id)); //自动加减库存
                if ($goodmbvcount <= 0) {
                    $data['code'] = '10001';
                    $msg = '操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
                //添加交易记录
                $usertradelog = new UserTradelog;
                $usertradelog->user_id = $order_arr->user_id;
                $usertradelog->type = 2;
                $usertradelog->order_num = $order_arr->order_num;
                $usertradelog->created_at = time();
                $usertradelog->money = - $order_arr->order_total_price;
                $usertradelog->save();
    
                $transaction->commit();
    
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
    
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    
    /**
     * 用户支付宝支付订单  token order_id 
     */
    public function actionAliPaymentOrder()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        $user_data['username'] = $user->username;
    
        $model = new AlipaymentOrderForm();
        
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$user_data['order_id']])->one();
            //验证订单是否是当前用户
            if ($order_arr->user_id != $user->id) {
                return array('code' => '10001', 'msg' => '您无权限操作该订单');
            }
            //验证订单状态
            switch ($order_arr->status) {
                case 2: return array('code' => '10001', 'msg' => '该订单为待发货状态,不可支付'); break;
                case 3: return array('code' => '10001', 'msg' => '该订单为发货状态,不可支付'); break;
                case 4: return array('code' => '10001', 'msg' => '该订单为已完成状态,不可支付'); break;
                case 5: return array('code' => '10001', 'msg' => '该订单为已出库状态,不可支付'); break;
            }
            //验证库存是否充足
            $goodmbv = GoodMbv::findOne($order_arr->mbv_id);
            if ( ! $goodmbv) return array('code' => '10001', 'msg' => '商品信息不存在');
            $goodmbv->refresh();
            if($goodmbv->stock_num < $order_arr->pay_num){
                return array('code' => '10001', 'msg' => '库存不足');
            }
            //支付宝 开始
            $aop = new AopClient;
            $aop->gatewayUrl = Yii::$app->params['gatewayUrl'];
            $aop->appId = Yii::$app->params['appId'];
            $aop->rsaPrivateKey = Yii::$app->params['rsaPrivateKey'];
            $aop->format = Yii::$app->params['format'];
            //$aop->charset = Yii::$app->params['charset'];
            $aop->signType = Yii::$app->params['signType'];
            $aop->alipayrsaPublicKey = Yii::$app->params['alipayrsaPublicKey'];
            //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
            $request = new AlipayTradeAppPayRequest();
            //SDK已经封装掉了公共参数，这里只需要传入业务参数
            $bizcontent = json_encode([
                'body'=>$user_data['order_id'],
                //'subject'=>'App支付测试',
                'subject'=>'商品订单号:'.$user_data['order_id'],
                'out_trade_no'=>$user_data['order_id'],//此订单号为商户唯一订单号
                //'total_amount'=> 0.01,//保留两位小数
                'total_amount'=> $order_arr->order_total_price,//保留两位小数
                'product_code'=>'QUICK_MSECURITY_PAY'
            ]);
            $request->setNotifyUrl("http://api.hexintrade.com/v1/user/ali-payment-notify-order");
            $request->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute
            $response = $aop->sdkExecute($request);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题echo htmlspecialchars($response);
            //echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
            //支付宝结束
            
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = htmlspecialchars($response);
            return $data;
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 用户支付宝支付订单异步通知 
     */
    public function actionAliPaymentNotifyOrder()
    {
        $aop = new AopClient;
        $aop->alipayrsaPublicKey = Yii::$app->params['alipayrsaPublicKey'];
        $signVerified = $aop->rsaCheckV1(Yii::$app->request->post(), NULL, "RSA2");
        if ($signVerified) {
            $app_id = Yii::$app->request->post('app_id');
            $out_trade_no = Yii::$app->request->post('out_trade_no');
            $total_amount = Yii::$app->request->post('total_amount');
            $trade_status = Yii::$app->request->post('trade_status');
            if ($app_id != Yii::$app->params['appId']) {
                echo 'fail';
                $data['code'] = '10001';
                $data['msg'] = 'appid验证失败';
                return $data;
            }
            if ( ! $out_trade_no) {
                echo 'fail';
                $data['code'] = '10001';
                $data['msg'] = '异步通知信息错误';
                return $data;
            }
            //查询订单信息
            $order_arr = Order::find()->select(['*'])->where(['order_num'=>$out_trade_no])->one();
            if ( ! $order_arr) {
                echo 'fail';
                $data['code'] = '10001';
                $data['msg'] = '订单信息不存在';
                return $data;
            }
            if ($total_amount != $order_arr->order_total_price) {
                echo 'fail';
                $data['code'] = '10001';
                $data['msg'] = '支付金额不符';
                return $data;
            }
            switch ($order_arr->status) {
                case 2: case 3: case 4: case 5:
                    echo 'success';
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                    break;
            }
            switch ($trade_status) {
                case 'TRADE_SUCCESS':
                case 'TRADE_FINISHED':
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        //订单信息
                        $orderData = Order::findOne($order_arr->id);
                        $orderData->status = 2; //订单已支付
                        $orderData->pay_at = time();
                        $orderData->save();
                        //修改商品库存
                        $goodmbv = new GoodMbv();
                        $goodmbvcount = $goodmbv->updateAllCounters(array('stock_num'=>-$order_arr->pay_num), 'id=:id', array(':id' => $order_arr->mbv_id)); //自动加减库存
                        if ($goodmbvcount <= 0) {
                            echo 'fail';
                            $data['code'] = '10001';
                            $data['msg'] = '操作失败';
                            return $data;
                        }
                        //添加交易记录
                        $usertradelog = new UserTradelog;
                        $usertradelog->user_id = $order_arr->user_id;
                        $usertradelog->type = 2;
                        $usertradelog->order_num = $order_arr->order_num;
                        $usertradelog->created_at = time();
                        $usertradelog->money = - $order_arr->order_total_price;
                        $usertradelog->save();
                    
                        $transaction->commit();
                    
                        echo 'success';
                        $data['code'] = '200';
                        $data['msg'] = '';
                        return $data;
                    }  catch(Exception $e) {
                        # 回滚事务
                        $transaction->rollback();
                    
                        echo 'fail';
                        $data['code'] = '10001';
                        $data['msg'] = $e->getMessage();
                        return $data;
                    }
                    break;
                case 'TRADE_CLOSED':
                    echo 'success';
                    $data['code'] = '10001';
                    $data['msg'] = '订单交易关闭';
                    return $data;
                    break;
                case 'WAIT_BUYER_PAY':
                    echo 'success';
                    $data['code'] = '10001';
                    $data['msg'] = '订单待支付';
                    return $data;
                    break;
            }
        } else {
            echo 'fail';
            $data['code'] = '10001';
            $data['msg'] = '签名验证失败';
            return $data;
        }
    }
    /**
     * 获取商家商品列表  token page good_status
     */
    public function actionBusinessGoodlist()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $good_status = isset($user_data['good_status']) && $user_data['good_status'] ? $user_data['good_status'] : 0;
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $page = (int)Yii::$app->request->post("page", '1');
        if($page <= 0){
            $good['code'] = '10001';
            $good['msg'] = '分页参数错误';
            return $good;
        }
        //分页
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => GoodMb::find()->select(['*'])->where(['user_id'=>$user->id,'mb_status'=>$good_status,'is_del'=>0])->count(),
            'page' =>$page - 1,
        ]);
        //获取商家商品信息
        $good_arr = GoodMb::find()->select(['*'])->where(['user_id'=>$user->id,'mb_status'=>$good_status,'is_del'=>0])->with([
            'good'=> function ($query) {
                $query->select(['*'])->where(['is_del' => 0])->with([
                    'goodImage'=> function ($query){
                        $query->select(['id','good_id','image_url']);
                     }
                ]);
            },
            'goodMbv'=> function ($query){
                $query->select(['*'])->orderBy('price asc');
            },
            'order'=> function ($query){
                $query->select(['id','mb_id','pay_num'])->where(['status' =>4])->count();
            }
        ])
        ->orderBy('created_at desc')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->Asarray()
        ->all();
        //print_r($good_arr);exit();
        if($good_arr){
            foreach ($good_arr as $k => $v){
                //商家报价id
                $goods['good'][$k]['mb_id']=$v['id'];
                //商品id
                $goods['good'][$k]['good_id']=$v['good']['id'];
                //商品名称
                $goods['good'][$k]['title']=$v['good']['title'];
                //商品码
                $goods['good'][$k]['good_num']=$v['good']['good_num'];
                //发布时间
                $goods['good'][$k]['created_at']=$v['created_at'];
                //总库存
                $goods['good'][$k]['stock_sum'] = $this->actionArrvalsum($v['goodMbv'] , 'stock_num');
                //总销量
                $goods['good'][$k]['sales_volume'] = $this->actionArrvalsum($v['order'] , 'pay_num');
                //商品最低价格
                $goods['good'][$k]['price']=isset($v['goodMbv'][0]['price'])?$v['goodMbv'][0]['price']:0;
                //获取商品图片
                $goods['good'][$k]['goodimage']=$v['good']['goodImage']['image_url'];
            }
            $good['code'] = '200';
            $good['msg'] = '';
            $good['data'] = $goods;
            return $good;
        }else{
            $good['code'] = '200';
            $good['msg'] = '';
            $good['data'] = [];
            return $good;
        }
    }
    /**
     * 获取快递公司信息
     */
    public function actionGetExpress()
    {
        
        $express_arr = Express::find()
        ->select(['id','name'])->where(['status' => 0])
        ->orderBy(['created_at' => SORT_DESC])
        ->asArray()
        ->all();
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $express_arr;
        return $data;
    }
    /**
     * 商家商品上下架操作 token mb_id
     */
    public function actionBusinessGoodset()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $mb_id = isset($user_data['mb_id']) && $user_data['mb_id'] ? $user_data['mb_id'] : 0;
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $model = GoodMb::find()->select(['*'])->where(['mb_status'=>[0,1],'user_id' => $user->id,'id' => $mb_id,'is_del' => '0'])->one();
        //print_r($model);exit();
        if (!$model){
            $data['code'] = '10001';
            $data['msg'] = '无此报价信息';
            return $data;
        }
        if($model->mb_status == 0){
            $mb_status = 1;
        }elseif($model->mb_status == 1){
            $mb_status = 0;
        }
        $model->mb_status = $mb_status;
        if($model->save()){
            $data['code'] = '200';
            $data['msg'] = '';
            return $data;
        }else{
            $data['code'] = '10001';
            //$data['msg'] = $model->getErrors();
            $data['msg'] ='操作失败';
            return $data;
        }
    }
    /**
     * 条形码搜索商品 token bar_code
     */
    public function actionBusinessCodeGood()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $bar_code = isset($user_data['bar_code']) && $user_data['bar_code'] ? $user_data['bar_code'] : '';
        if(!$bar_code){
            $data['code'] = '10001';
            $data['msg'] = '条形码不能为空';
            return $data;
        }
        //根据条形码去条形码库中查找商品
        $goodcode = GoodCode::find(['good_id'])->where(['bar_code' =>$bar_code])->one();
        $good_id = $goodcode['good_id'];
        //查询商品信息
        $good_arr = Good::find()->select(['*'])->where(['id'=>$good_id,'is_del'=>0])->with([
            'goodImage'=> function ($query){
                $query->select(['image_url']);
            },
            'cate'=> function ($query){
                $query->select(['*']);
            },
            'brand'=> function ($query){
                $query->select(['*']);
            },
            'goodCode'=> function ($query){
                $query->select(['*']);
            },
        ])
        ->one();
        if(!$good_arr){
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] =[];
            return $data;
        }
        //商品属性信息
        $goodMbv = array();
        $goodCode = isset($good_arr->goodCode) ? $good_arr->goodCode : array();
        if ($goodCode) {
            foreach ($goodCode as $codeKey => $codeValue) {
                $goodMbv[$codeKey]['model_text'] = $codeValue->model_text;
                $goodMbv[$codeKey]['price'] = 0;
                $goodMbv[$codeKey]['stock_num'] = 0;
                $goodMbv[$codeKey]['bar_code'] = $codeValue->bar_code;
            }
        }
        //商品id
        $goods['good_id'] = $good_arr->id;
        //商品标题
        $goods['title'] = $good_arr->title;
        //商品图片
        $goods['goodimage']=$good_arr->goodImage->image_url;
        //商品详细
        $goods['description']=$good_arr->description;
        //商品码
        $goods['good_num']=$good_arr->good_num;
        //分类id及名称
        $goods['cate_id']=$good_arr->cate->id;
        $goods['cate_name']=$good_arr->cate->title;
        //品牌id及名称
        $goods['brand_id']=$good_arr->brand->id;
        $goods['brand_name']=$good_arr->brand->title;
        
        $goods['goodmbv'] = $goodMbv;
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $goods;
        return $data;
        
    }
    /**
     * 根据商品id搜索商品 token good_id
     */
    public function actionBusinessIdGood()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $good_id = isset($user_data['good_id']) && $user_data['good_id'] ? $user_data['good_id'] : '';
        if(!$good_id){
            $data['code'] = '10001';
            $data['msg'] = '商品id不能为空';
            return $data;
        }
//         //根据条形码去条形码库中查找商品
//         $goodcode = GoodCode::find(['good_id'])->where(['bar_code' =>$bar_code])->one();
//         $good_id = $goodcode['good_id'];
        //查询商品信息
        $good_arr = Good::find()->select(['*'])->where(['id'=>$good_id,'is_del'=>0])->with([
            'goodImage'=> function ($query){
            $query->select(['image_url']);
            },
            'cate'=> function ($query){
            $query->select(['*']);
            },
            'brand'=> function ($query){
            $query->select(['*']);
            },
            'goodCode'=> function ($query){
            $query->select(['*']);
            },
            ])
            ->one();
            if(!$good_arr){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] =[];
                return $data;
            }
            //商品属性信息
            $goodMbv = array();
            $goodCode = isset($good_arr->goodCode) ? $good_arr->goodCode : array();
            if ($goodCode) {
                foreach ($goodCode as $codeKey => $codeValue) {
                    $goodMbv[$codeKey]['model_text'] = $codeValue->model_text;
                    $goodMbv[$codeKey]['price'] = 0;
                    $goodMbv[$codeKey]['stock_num'] = 0;
                    $goodMbv[$codeKey]['bar_code'] = $codeValue->bar_code;
                }
            }
            //商品id
            $goods['good_id'] = $good_arr->id;
            //商品标题
            $goods['title'] = $good_arr->title;
            //商品图片
            $goods['goodimage']=$good_arr->goodImage->image_url;
            //商品详细
            $goods['description']=$good_arr->description;
            //商品码
            $goods['good_num']=$good_arr->good_num;
            //分类id及名称
            $goods['cate_id']=$good_arr->cate->id;
            $goods['cate_name']=$good_arr->cate->title;
            //品牌id及名称
            $goods['brand_id']=$good_arr->brand->id;
            $goods['brand_name']=$good_arr->brand->title;
    
            $goods['goodmbv'] = $goodMbv;
    
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = $goods;
            return $data;
    
    }
    /**
     * 商家添加报价 token good_id address_id date
     * {"goodmbv":[{"model_text":"型号1","price":"111","stock_num":"50","bar_code":"21231231231"},{"model_text":"型号2","price":"222","stock_num":"50","bar_code":"3123123123123"}]}
     */
    public function actionBusinessCreateGoodMb()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
    
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        //验证该商品是否存在
        $good = Good::find()->where(['id' => $user_data['good_id'],'is_del'=>0])->one();
        if(!$good){
            $data['code'] = '10001';
            $data['msg'] = '商品不存在';
            return $data;
        }
        //查询该商品该用户是否已经报过价
        $goodmb = GoodMb::find()->select(['*'])->where(['good_id'=>$user_data['good_id'],'mb_status'=>[0,1],'user_id' => $user->id,'is_del'=>0])->one();
        if($goodmb){
            $data['code'] = '10001';
            $data['msg'] = '每一件商品每一位用户只能报价一次';
            return $data;
        }
        
        //查询该商品的所有条形码
        $bar_code = array();
        $bar_codes = GoodCode::find()->select(['bar_code'])->where(['good_id' => $good['id']])->all();
        foreach ($bar_codes as $key => $v){
            $bar_code[$key] = $v->bar_code;
        }
        
        $model = new BusinessUpdateGoodForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
    
            if(empty($user_data['data'])){
                $data['code'] = '10001';
                $data['msg'] = '商品属性数据不能为空';
                return $data;
            }
            $goodmbv_arr = json_decode($user_data['data'],true);
    
            $transaction = \Yii::$app->db->beginTransaction();
            try {

                $goodmb = new GoodMb();
                $goodmb->user_id=$user->id;
                //$goodmb->freight_id=$user_data['freight_id'];
                $goodmb->good_id = $good->id;
                $goodmb->cate_id=$good->cate_id;
                $goodmb->brand_id=$good->brand_id;
                //$goodmb->place_id=$user_data['place_id'];
                $goodmb->address_id=$user_data['address_id'];
                $goodmb->mb_status=0;
                $goodmb->created_at=time();
                $goodmb->updated_at=time();
                if(!$goodmb->save()){
                    $data['code'] = '10001';
                    $msg = array_values($goodmb->getFirstErrors())[0];
                    $data['msg'] = $msg;
                    return $data;
                }
    
                $goodmbvArr = array();
                foreach ($goodmbv_arr['goodmbv'] as $key => $attributes) {
                    $goodmbvArr[$key]['model_text'] = $attributes['model_text'];
                    $goodmbvArr[$key]['price'] = $attributes['price'];
                    $goodmbvArr[$key]['stock_num'] = $attributes['stock_num'];
                    $goodmbvArr[$key]['bar_code'] = $attributes['bar_code'];
                    $goodmbvArr[$key]['mb_id'] = $goodmb->id;
                    $goodmbvArr[$key]['created_at'] = time();
                    $goodmbvArr[$key]['updated_at'] = time();
                    if(!in_array($attributes['bar_code'], $bar_code)){
                        $goodmbvArr[$key]['status'] = 2;
                    }else{
                        $goodmbvArr[$key]['status'] = 0;
                    }
                    
                }
                //print_r($goodmbvArr);exit();
                if ($goodmbvArr) {
                    $connection = \Yii::$app->db;
                    //数据批量入库
                    $connection->createCommand()->batchInsert('{{%good_mbv}}',['model_text','price','stock_num','bar_code','mb_id','created_at','updated_at','status'],$goodmbvArr)->execute();
                }
                $transaction->commit();
    
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
    
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 商家发布新商品 token image_url title description good_num cate_id brand_id freight_id address_id place_id date
     * {"goodmbv":[{"model_text":"型号1","price":"111","stock_num":"50","bar_code":"21231231231"},{"model_text":"型号2","price":"222","stock_num":"50","bar_code":"3123123123123"}]}
     */
    public function actionBusinessCreateGood()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);

        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $model = new BusinessCreateGoodForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            
            if(empty($user_data['data'])){
                $data['code'] = '10001';
                $data['msg'] = '商品属性数据不能为空';
                return $data;
            }
            $goodmbv_arr = json_decode($user_data['data'],true);
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $good = new Good();
                $good->user_id=$user->id;
                $good->good_num=$user_data['good_num'];
                $good->title=$user_data['title'];
                $good->description=$user_data['description'];
                $good->cate_id=$user_data['cate_id'];
                $good->brand_id=$user_data['brand_id'];
                $good->created_at=time();
                $good->updated_at=time();
                if(!$good->save()){
                    $data['code'] = '10001';
                    $msg = array_values($good->getFirstErrors())[0];
                    $data['msg'] = $msg;
                    return $data;
                }
                
                $goodclicks = new GoodClicks();
                $goodclicks->good_id=$good->id;
                if(!$goodclicks->save()){
                    $data['code'] = '10001';
                    $msg = array_values($good->getFirstErrors())[0];
                    $data['msg'] = $msg;
                    return $data;
                }
                
                $goodimage = new GoodImage();
                $goodimage->good_id=$good->id;
                $goodimage->image_url=$user_data['image_url'];
                $goodimage->save();
                if(!$goodimage->save()){
                    $data['code'] = '10001';
                    $msg = array_values($goodimage->getFirstErrors())[0];
                    $data['msg'] = $msg;
                    return $data;
                }
                
                $goodmb = new GoodMb();
                $goodmb->user_id=$user->id;
                //$goodmb->freight_id=$user_data['freight_id'];
                $goodmb->address_id=$user_data['address_id'];
                $goodmb->good_id = $good->id;
                $goodmb->cate_id=$user_data['cate_id'];
                $goodmb->brand_id=$user_data['brand_id'];
                //$goodmb->place_id=$user_data['place_id'];
                $goodmb->status=1;
                $goodmb->mb_status=0;
                $goodmb->created_at=time();
                $goodmb->updated_at=time();
                if(!$goodmb->save()){
                    $data['code'] = '10001';
                    $msg = array_values($goodmb->getFirstErrors())[0];
                    $data['msg'] = $msg;
                    return $data;
                }
                
                $goodmbvArr = array();
                foreach ($goodmbv_arr['goodmbv'] as $key => $attributes) {
                    //print_r($attributes);exit();
                    $goodmbvArr[$key]['model_text'] = $attributes['model_text'];
                    $goodmbvArr[$key]['price'] = $attributes['price'];
                    $goodmbvArr[$key]['stock_num'] = $attributes['stock_num'];
                    $goodmbvArr[$key]['bar_code'] = $attributes['bar_code'];
                    $goodmbvArr[$key]['mb_id'] = $goodmb->id;
                    $goodmbvArr[$key]['created_at'] = time();
                    $goodmbvArr[$key]['updated_at'] = time();
                }
                //print_r($goodmbvArr);exit();
                if ($goodmbvArr) {
                    $connection = \Yii::$app->db;
                    //数据批量入库
                    $connection->createCommand()->batchInsert('{{%good_mbv}}',['model_text','price','stock_num','bar_code','mb_id','created_at','updated_at'],$goodmbvArr)->execute();
                }
                $transaction->commit();
            
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }  catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
            
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }     
    }
     
    /**
     * 商家更新商品 token mb_id place_id freight_id address_id date
     * {"goodmbv": [{"id":"21","model_text": "型号1","price": "111","stock_num": "50","bar_code": "21231231231","is_del":"1"},{"id":"22","model_text": "型号2","price": "222","stock_num": "50","bar_code": "3123123123123"},{"id":"","model_text": "型号3","price": "333","stock_num": "50","bar_code": "3123123123123"}]}
     */
    public function actionBusinessUpdateGood()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $mb_id = isset($user_data['mb_id']) && $user_data['mb_id'] ? $user_data['mb_id'] : 0;
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        $goodmb = GoodMb::find()->select(['*'])->where(['mb_status'=>[0,1],'status'=>0,'user_id' => $user->id,'id' => $mb_id,'is_del'=>0])->one();
        if (!$goodmb){
            $data['code'] = '10001';
            $data['msg'] = '无此报价信息';
            //$data['msg'] = json_encode($user_data);
            return $data;
        }
        //查询该商品的所有条形码
        $bar_code = array();
        $bar_codes = GoodCode::find()->select(['bar_code'])->where(['good_id' => $goodmb->good_id])->all();
        foreach ($bar_codes as $key => $v){
            $bar_code[$key] = $v->bar_code;
        }
        $model = new BusinessUpdateGoodForm();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //更新数据
            if(empty($user_data['data'])){
                $data['code'] = '10001';
                $data['msg'] = '商品属性数据不能为空';
                return $data;
            }
            $goodmbv_arr = json_decode($user_data['data'],true);
            //获取商家商品信息
            $good_arr = GoodMb::find()->select(['id','good_id'])->where(['user_id'=>$user->id,'status'=>[0,1],'is_del'=>0])->with([
                'good'=> function ($query) {
                $query->select(['id'])->where(['is_del'=>0])->with([
                    'goodImage'=> function ($query){
                        $query->select(['id','good_id']);
                    }
                    ]);
                }
                ])
                ->one();
                if (empty($good_arr)) {
                    $data['code'] = '10001';
                    $data['msg'] = '无此报价信息';
                    return $data;
                }
    
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $good = Good::findOne($good_arr->good->id);
                    $good->updated_at=time();
                    if(!$good->save()){
                        $data['code'] = '10001';
                        $msg = array_values($good->getFirstErrors())[0];
                        $data['msg'] = $msg;
                        return $data;
                    }
                                          
                    $goodmb = GoodMb::findOne($good_arr->id);
                    //$goodmb->freight_id=$user_data['freight_id'];
                    $goodmb->address_id=$user_data['address_id'];
                    //$goodmb->place_id=$user_data['place_id'];
                    $goodmb->mb_status=0;
                    $goodmb->updated_at=time();
                    if(!$goodmb->save()){
                        $data['code'] = '10001';
                        $msg = array_values($goodmb->getFirstErrors())[0];
                        $data['msg'] = $msg;
                        return $data;
                    }
                    //print_r($goodmbv_arr['goodmbv']);exit();
                    $goodmbvArr = array();
                    foreach($goodmbv_arr['goodmbv'] as $key => $attributes) {
                        if ( ! isset($attributes['model_text']) ||  ! isset($attributes['price']) ||  ! isset($attributes['stock_num']) ||  ! isset($attributes['bar_code'])) {
                            $data['code'] = '10001';
                            $data['msg'] = '商品属性有误';
                            return $data;
                        }
                        //修改数据
                        $goodmbvEditArr = array();
                        $goodmbvEditArr['model_text'] = $attributes['model_text'];
                        $goodmbvEditArr['price'] = $attributes['price'];
                        $goodmbvEditArr['stock_num'] = $attributes['stock_num'];
                        $goodmbvEditArr['bar_code'] = $attributes['bar_code'];
                        $goodmbvEditArr['updated_at'] = time();
                        //print_r($goodmbvEditArr);exit();
                        if(isset($attributes['id']) && $attributes['id']){
                            $query = GoodMbv::updateAll($goodmbvEditArr, 'mb_id=:mb_id AND id=:id', array(':mb_id' => $good_arr->id, ':id' => $attributes['id']));
                            //print_r($query);exit();  
                        }
                        if ( ! isset($attributes['id']) || (isset($attributes['id']) && empty($attributes['id']))) {
                            //添加数据
                            $goodmbvArr[$key]['model_text'] = $attributes['model_text'];
                            $goodmbvArr[$key]['price'] = $attributes['price'];
                            $goodmbvArr[$key]['stock_num'] = $attributes['stock_num'];
                            $goodmbvArr[$key]['bar_code'] = $attributes['bar_code'];
                            $goodmbvArr[$key]['mb_id'] = $good_arr->id;
                            $goodmbvArr[$key]['created_at'] = time();
                            $goodmbvArr[$key]['updated_at'] = time();
                            if (!in_array($attributes['bar_code'], $bar_code)) {
                                $goodmbvArr[$key]['status'] = 2;
                            } else {
                                $goodmbvArr[$key]['status'] = 0;
                            }
                        }
                    }
                     
                    if ($goodmbvArr) {
                        $connection = \Yii::$app->db;
                        //数据批量入库
                        $connection->createCommand()->batchInsert('{{%good_mbv}}',['model_text','price','stock_num','bar_code','mb_id','created_at','updated_at', 'status'],$goodmbvArr)->execute();
                    }
                    $transaction->commit();
                     
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                } catch(Exception $e) {
                    # 回滚事务
                    $transaction->rollback();
    
                    $data['code'] = '10001';
                    $data['msg'] = $e->getMessage();
                    return $data;
                }
        } else {
            $data['code'] = '10001';
            $msg = array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
    }
    /**
     * 商家删除报价 token mb_id
     */
        public function actionBusinessDeleteGoodMb()
        {
            $user_data = Yii::$app->request->post();
            $token = $user_data['token'];
            $user = User::findIdentityByAccessToken($token);
            $mb_id = isset($user_data['mb_id']) && $user_data['mb_id'] ? $user_data['mb_id'] : 0;
            //验证是否为商家用户
            $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
            if(!$business){
                $data['code'] = '10001';
                $data['msg'] = '不是商家用户或未通过商家审核';
                return $data;
            }
            $goodmb = GoodMb::find()->select(['*'])->where(['mb_status'=>[0,1],'status'=>0,'user_id' => $user->id,'id' => $mb_id,'is_del'=>0])->one();
            if (!$goodmb){
                $data['code'] = '10001';
                $data['msg'] = '无此报价信息';
                return $data;
            }
            if($goodmb->mb_status==0){
                $data['code'] = '10001';
                $data['msg'] = '只能删除下架的报价';
                return $data;
            }
            if($goodmb->is_del==1){
                $data['code'] = '10001';
                $data['msg'] = '此报价已删除';
                return $data;
            }
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //修改商品报价删除状态
                $goodmb = GoodMb::find()->where(['id'=>$mb_id])->one();
                $goodmb->is_del = 1;
                if($goodmb->save()){
                    //修改商品属性删除状态
                    $goodmbv = GoodMbv::find()->where(['in', 'mb_id', $goodmb->id])->all();
                    foreach ($goodmbv as $mbv) {
                        $mbvEdit = array();
                        $mbvEdit['is_del'] = 1;
                        GoodMbv::updateAll($mbvEdit, 'id=:id', array(':id' => $mbv->id));
                    }
                    $transaction->commit();
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                }else{
                    $data['code'] = '10001';
                    $data['msg'] = '操作失败';
                    return $data;
                }

            } catch(Exception $e) {
                # 回滚事务
                $transaction->rollback();
                $data['code'] = '10001';
                $data['msg'] = '操作失败';
                return $data;
            }
        }
        /**
         * 用户反馈 token text_a
         */
        public function actionFeedback()
        {
            $user_data = Yii::$app->request->post();
            $token = $user_data['token'];
            $user = User::findIdentityByAccessToken($token);
            if(empty($user_data['text_a'])){
                $data['code'] = '10001';
                $data['msg'] = '反馈内容不能为空';
                return $data;
            }
            $feedback = new Feedback();
            $feedback->text_a =$user_data['text_a'];
            $feedback->created_at = time();
            if($feedback->save()){
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else{
                $data['code'] = '10001';
                $data['msg'] = '操作失败';
                return $data;
            }
        }
       /**
        * 商家更新商品 token mb_id image_url title description good_num cate_id brand_id place_id freight_id date
        * {"goodmbv": [{"id":"21","model_text": "型号1","price": "111","stock_num": "50","bar_code": "21231231231"},{"id":"22","model_text": "型号2","price": "222","stock_num": "50","bar_code": "3123123123123"},{"id":"","model_text": "型号3","price": "333","stock_num": "50","bar_code": "3123123123123"}]}
       */
//     public function actionBusinessUpdateGood()
//     {
//         $user_data = Yii::$app->request->post();
//         $token = $user_data['token'];
//         $user = User::findIdentityByAccessToken($token);
//         $mb_id = isset($user_data['mb_id']) && $user_data['mb_id'] ? $user_data['mb_id'] : 0;
//         //验证是否为商家用户
//         $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
//         if(!$business){
//             $data['code'] = '10001';
//             $data['msg'] = '不是商家用户或未通过商家审核';
//             return $data;
//         }
//         $goodmb = GoodMb::find()->select(['*'])->where(['status'=>[0,1],'user_id' => $user->id,'id' => $mb_id])->one();
//         if (!$goodmb){
//             $data['code'] = '10001';
//             $data['msg'] = '无此报价信息';
//             return $data;
//         }
//         $model = new BusinessCreateGoodForm();
//         $model->setAttributes($user_data);
//         if ($user_data && $model->validate()) {
//             //更新数据
//             if(empty($user_data['data'])){
//                 $data['code'] = '10001';
//                 $data['msg'] = '商品属性数据不能为空';
//                 return $data;
//             }
//             $goodmbv_arr = json_decode($user_data['data'],true);
//             //获取商家商品信息
//             $good_arr = GoodMb::find()->select(['id','good_id'])->where(['user_id'=>$user->id,'status'=>[0,1]])->with([
//                 'good'=> function ($query) {
//                     $query->select(['id'])->with([
//                         'goodImage'=> function ($query){
//                             $query->select(['id','good_id']);
//                         }
//                     ]);
//                 }
//             ])
//             ->one();
//             if (empty($good_arr)) {
//                 $data['code'] = '10001';
//                 $data['msg'] = '无此报价信息';
//                 return $data;
//             }
            
//             $transaction = \Yii::$app->db->beginTransaction();
//             try {
//                 $good = Good::findOne($good_arr->good->id);
//                 $good->user_id=$user->id;
//                 $good->good_num=$user_data['good_num'];
//                 $good->title=$user_data['title'];
//                 $good->description=$user_data['description'];
//                 $good->cate_id=$user_data['cate_id'];
//                 $good->brand_id=$user_data['brand_id'];
//                 $good->updated_at=time();
//                 if(!$good->save()){
//                     $data['code'] = '10001';
//                     $msg = array_values($good->getFirstErrors())[0];
//                     $data['msg'] = $msg;
//                     return $data;
//                 }
                 
//                 $goodimage = GoodImage::findOne($good_arr->good->goodImage->id);
//                 $goodimage->good_id=$good->id;
//                 $goodimage->image_url=$user_data['image_url'];
//                 $goodimage->save();
//                 if(!$goodimage->save()){
//                     $data['code'] = '10001';
//                     $msg = array_values($goodimage->getFirstErrors())[0];
//                     $data['msg'] = $msg;
//                     return $data;
//                 }
                 
//                 $goodmb = GoodMb::findOne($good_arr->id);
//                 $goodmb->user_id=$user->id;
//                 $goodmb->freight_id=$user_data['freight_id'];
//                 $goodmb->good_id = $good->id;
//                 $goodmb->cate_id=$user_data['cate_id'];
//                 $goodmb->brand_id=$user_data['brand_id'];
//                 $goodmb->place_id=$user_data['place_id'];
//                 $goodmb->mb_status=0;
//                 $goodmb->updated_at=time();
//                 if(!$goodmb->save()){
//                     $data['code'] = '10001';
//                     $msg = array_values($goodmb->getFirstErrors())[0];
//                     $data['msg'] = $msg;
//                     return $data;
//                 }
                
//                 $goodmbvArr = array();
//                 foreach($goodmbv_arr['goodmbv'] as $key => $attributes) {
//                     if ( ! isset($attributes['model_text']) ||  ! isset($attributes['price']) ||  ! isset($attributes['stock_num']) ||  ! isset($attributes['bar_code'])) {
//                         $data['code'] = '10001';
//                         $data['msg'] = '商品属性有误';
//                         return $data;
//                     }
//                     //修改数据
//                     $goodmbvEditArr = array();
//                     $goodmbvEditArr['model_text'] = $attributes['model_text'];
//                     $goodmbvEditArr['price'] = $attributes['price'];
//                     $goodmbvEditArr['stock_num'] = $attributes['stock_num'];
//                     $goodmbvEditArr['bar_code'] = $attributes['bar_code'];
//                     $goodmbvEditArr['updated_at'] = time();
//                     if(isset($attributes['is_del']) && $attributes['is_del'] == 1 && isset($attributes['id']) && $attributes['id']){
//                         $goodmbvEditArr['status'] = 3;
//                     }
//                     if(isset($attributes['id']) && $attributes['id']){
//                         GoodMbv::updateAll($goodmbvEditArr, 'mb_id=:mb_id AND id=:id', array(':mb_id' => $good_arr->id, ':id' => $attributes['id']));
//                     }
//                     if ( ! isset($attributes['id']) || (isset($attributes['id']) && empty($attributes['id']))) {
//                         //添加数据
//                         $goodmbvArr[$key]['model_text'] = $attributes['model_text'];
//                         $goodmbvArr[$key]['price'] = $attributes['price'];
//                         $goodmbvArr[$key]['stock_num'] = $attributes['stock_num'];
//                         $goodmbvArr[$key]['bar_code'] = $attributes['bar_code'];
//                         $goodmbvArr[$key]['mb_id'] = $good_arr->id;
//                         $goodmbvArr[$key]['created_at'] = time();
//                         $goodmbvArr[$key]['updated_at'] = time();
//                     }
//                 }
                 
//                 if ($goodmbvArr) {
//                     $connection = \Yii::$app->db;
//                     //数据批量入库
//                     $connection->createCommand()->batchInsert('{{%good_mbv}}',['model_text','price','stock_num','bar_code','mb_id','created_at','updated_at'],$goodmbvArr)->execute();
//                 }
//                 $transaction->commit();
                 
//                 $data['code'] = '200';
//                 $data['msg'] = '';
//                 return $data;
//             } catch(Exception $e) {
//                 # 回滚事务
//                $transaction->rollback();
            
//                $data['code'] = '10001';
//                $data['msg'] = $e->getMessage();
//                return $data;
//            }
//         } else {
//            $data['code'] = '10001';
//            $msg = array_values($model->getFirstErrors())[0];
//            $data['msg'] = $msg;
//            return $data;
//         }  
//     }
    /**
     * 获取商家商品详细  token  mb_id
     */
    public function actionBusinessGoodview()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $mb_id = isset($user_data['mb_id']) && $user_data['mb_id'] ? $user_data['mb_id'] : 0;
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        //获取商家商品信息
        $good_arr = GoodMb::find()->select(['*'])->where(['user_id'=>$user->id,'mb_status'=>[0,1],'status'=>0,'id'=>$mb_id,'is_del'=>0])->with([
            'good'=> function ($query) {
                $query->select(['*'])->where(['is_del'=>0])->with([
                    'goodImage'=> function ($query){
                        $query->select(['id','good_id','image_url']);
                    },
                    'cate'=> function ($query){
                        $query->select(['*']);
                    },
                    'brand'=> function ($query){
                        $query->select(['*']);
                    },
                    'goodCode'=> function ($query){
                        $query->select(['*']);
                    },
                ]);
            },
            'goodMbv'=> function ($query){
                $query->select(['*'])->where(['is_del'=>0])->orderBy('price asc');
            },
//             'place'=> function ($query){
//                 $query->select(['*']);
//             },
//             'freight'=> function ($query){
//                 $query->select(['*']);
//             },
            'address'=> function ($query){
                $query->select(['*']);
            },
            ])
            ->Asarray()
            ->one();
            //print_r($good_arr['goodMbv']);exit();
            if (empty($good_arr)) {
                $data['code'] = '10001';
                $data['msg'] = '无此报价信息';
                return $data;
            }
            
            $goodMbvs  = array();
            //商品条形码信息
            $goodBarCode = isset($good_arr['good']['goodCode']) ? $good_arr['good']['goodCode'] : array();
            //商品属性信息
            $goodMbv = isset($good_arr['goodMbv']) ? $good_arr['goodMbv'] : array();
            //print_r($goodMbv);exit();
            if ($goodBarCode && $goodMbv) {
                foreach ($goodBarCode as $barcodeKey => $barcodeValue) {
                    foreach ($goodMbv as $goodMbvValue) {
                        $goodMbvs[$barcodeKey]['id'] = 0;
                        $goodMbvs[$barcodeKey]['model_text'] = $barcodeValue['model_text'];
                        $goodMbvs[$barcodeKey]['price'] = 0;
                        $goodMbvs[$barcodeKey]['stock_num'] = 0;
                        $goodMbvs[$barcodeKey]['bar_code'] = $barcodeValue['bar_code'];
                        if ($goodMbvValue['bar_code'] == $barcodeValue['bar_code']) {
                            $goodMbvs[$barcodeKey]['id'] = $goodMbvValue['id'];
                            $goodMbvs[$barcodeKey]['model_text'] = $goodMbvValue['model_text'];
                            $goodMbvs[$barcodeKey]['price'] = $goodMbvValue['price'];
                            $goodMbvs[$barcodeKey]['stock_num'] = $goodMbvValue['stock_num'];
                            $goodMbvs[$barcodeKey]['bar_code'] = $goodMbvValue['bar_code'];
                            break;
                        }
                    }
                }
            }else{
                if($good_arr['good']['id']>3||$good_arr['good']['id']<76){
                    foreach ($goodMbv as $k => $goodMbvValue) {
                        $goodMbvs[$k]['id'] = $goodMbvValue['id'];
                        $goodMbvs[$k]['model_text'] = $goodMbvValue['model_text'];
                        $goodMbvs[$k]['price'] = $goodMbvValue['price'];
                        $goodMbvs[$k]['stock_num'] = $goodMbvValue['stock_num'];
                        $goodMbvs[$k]['bar_code'] = $goodMbvValue['bar_code'];
                    }
                }
            }
            
            //print_r($goodMbvs);exit();
            //商家报价id
            $goods['good']['mb_id']=$good_arr['id'];
            //商品图片
            $goods['good']['goodimage']=$good_arr['good']['goodImage']['image_url'];
            //商品名称
            $goods['good']['title']=$good_arr['good']['title'];
            //商品详细
            $goods['good']['description']=$good_arr['good']['description'];
            //商品码
            $goods['good']['good_num']=$good_arr['good']['good_num'];
            //分类id及名称
            $goods['good']['cate_id']=$good_arr['good']['cate']['id'];
            $goods['good']['cate_name']=$good_arr['good']['cate']['title'];
            //品牌id及名称
            $goods['good']['brand_id']=$good_arr['good']['brand']['id'];
            $goods['good']['brand_name']=$good_arr['good']['brand']['title'];
            //发货地id及名称
//             $goods['good']['place_id']=$good_arr['place']['id'];
//             $goods['good']['place_name']=$good_arr['place']['name'];
//             $goods['good']['place_name_en']=$good_arr['place']['name_en'];
            //运费模版id及名称
//             $goods['good']['freight_id']=$good_arr['freight']['id'];
//             $goods['good']['freight_name']=$good_arr['freight']['title'];
            //仓库id及名称
            $goods['good']['address_id']=$good_arr['address']['id'];
            $goods['good']['address_name']=$good_arr['address']['name'];
            //商品属性
            $goods['good']['data'] = $goodMbvs;
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] = $goods;
            return $data;
    }
    /**
     * 获取七牛token
     */
    public function actionQiniuToken()
    {
        $accessKey = 'r1Mi9A9K5ACE7UrBB2Y5Hk5xF05OABhzgGt_mEQz';
        $secretKey = 'EpTrcmszAYQUfjQ2FlgV7yV2E0B9u65YH2NciqQB';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'hexin-image';
        // 生成上传Token
        $uploadtoken = $auth->uploadToken($bucket);
        
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data']['uploadtoken'] = $uploadtoken;
        return $data;
    }
    /**
     * 获取转运仓列表或某一个转运仓信息
     */
    public function actionTransferBin()
    {
        $user_data = Yii::$app->request->post();
        $address_id = isset($user_data['address_id']) ? $user_data['address_id'] : '';
        $address_arr = UserAddress::find()->select(['*'])->where(['user_id'=>0,'status'=>[0,1]])->filterWhere(['user_id'=>0,'status'=>[0,1],'id'=>$address_id])->asArray()->all();
        if($address_arr){
            foreach ($address_arr as $k => $v){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'][$k]['id'] = $v['id'];
                $data['data'][$k]['name'] = $v['name'];
                $data['data'][$k]['country_id'] = $v['country_id'];
                $data['data'][$k]['state_id'] = $v['state_id'];
                $data['data'][$k]['city_id'] = $v['city_id'];
                $data['data'][$k]['csc_name'] = $v['csc_name'];
                $data['data'][$k]['csc_name_en'] = $v['csc_name_en'];
                $data['data'][$k]['street'] = $v['street'];
                $data['data'][$k]['phone'] = $v['phone'];
                $data['data'][$k]['status'] = $v['status'];
            }
            return $data;
        }else{
            $data['code'] = '200';
            $data['msg'] = '';
            $data['data'] =[];
            return $data;
        }
    }
    
    /**
     * 获取商家统计信息  token
     */
    public function actionBusinessCounts()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        //验证是否为商家用户
        $business =Business::find()->select(['user_id'])->where(['user_id'=>$user->id,'status'=>1])->one();
        if(!$business){
            $data['code'] = '10001';
            $data['msg'] = '不是商家用户或未通过商家审核';
            return $data;
        }
        
        $businessCounts['good_counts'] = GoodMb::find()->select(['id'])->where(['user_id'=>$user->id,'is_del'=>0])->count();
        $businessCounts['order_counts'] = Order::find()->select(['id'])->where(['business_id'=>$user->id,'status'=>2])->count();
        $businessCounts['goodup_counts'] = GoodMb::find()->select(['id'])->where(['user_id'=>$user->id,'status'=>0,'mb_status'=>0,'is_del'=>0])->count();
        $businessCounts['gooddw_counts'] = GoodMb::find()->select(['id'])->where(['user_id'=>$user->id,'status'=>0,'mb_status'=>1,'is_del'=>0])->count();
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $businessCounts;
        return $data;
    }
    /**
     * 获取用户提现信息  token
     */
    public function actionUserWithdrawals()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);

        $data['code'] = '200';
        $data['msg'] = '';
        $data['data']['money'] =$user->money;
        $data['data']['commission_fee'] =$user->commission_fee;
        return $data; 
    }
    /**
     * 创建用户提现账户  token type account realname account_bank verifycode
     */
    public function actionCreateWithdrawalsAccount()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $model = new UserAccount();
        $user_data['username'] = $user->username;
        //print_r($user_data);exit;
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            $model->user_id = $user->id;
            $model->type = $user_data['type'];
            $model->account = $user_data['account'];
            $model->realname = $user_data['realname'];
            $model->account_bank = isset($user_data['account_bank'])?$user_data['account_bank']:'';
            $model->status = 0;
            $model->created_at = time();
            $model->updated_at = time();
            if($model->save()){
                $data['code'] = '200';
                $data['msg'] = '';
                return $data;
            }else {
                $data['code'] = '10001';
                $data['msg'] = '操作失败';
                return $data;
            } 
        }else {
                $data['code'] = '10001';
                $msg =array_values($model->getFirstErrors())[0];
                $data['msg'] = $msg;
                return $data;
            }   
    }
    /**
     * 删除用户提现账户  token id
     */
    public function actionDelWithdrawalsAccount()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        $withdrawals_id = isset($user_data['id']) ? $user_data['id'] : 0;
        //查询该条记录
        $withdrawals = UserAccount::find()->where(['user_id' => $user->id ,'id'=>$withdrawals_id ,'status' => 0])->one();
        if(empty($withdrawals)){
            $data['code'] = '10001';
            $data['msg'] = '提现账户不存在';
            return $data;
        }
        $withdrawals->status= 1;
        $withdrawals->updated_at = time();
        if($withdrawals->save()){
            $data['code'] = '200';
            $data['msg'] = '';
            return $data;
        }else {
            $data['code'] = '10001';
            $data['msg'] = '操作失败';
            return $data;
        }
    }
    /**
     * 获得用户提现账户列表  token
     */
    public function actionGetWithdrawalsAccount()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $withdrawals_arr = UserAccount::find()->where(['user_id' => $user->id,'status' => 0])->all();
        if($withdrawals_arr){
            foreach ($withdrawals_arr as $k =>$v){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'][$k]['id'] = $v->id;
                $data['data'][$k]['type'] = $v->type;
                $data['data'][$k]['account'] = $v->account;
                $data['data'][$k]['realname'] = $v->realname;
                $data['data'][$k]['account_bank'] = $v->account_bank;
            }
            //$data['data']['username'] = $user->username;
            return $data;
        }else{
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'] =[];
                return $data;
        }
    }
    /**
     * 提交提现申请  token account_id money_w
     */
    public function actionWithdrawalsApply()
    {
        $user_data = Yii::$app->request->post();
        $token = $user_data['token'];
        $user = User::findIdentityByAccessToken($token);
        
        $model = new UserWithdrawalsapply();
        $model->setAttributes($user_data);
        if ($user_data && $model->validate()) {
            //验证提现账户是否属于该用户
            $user_account = UserAccount::find()->where(['user_id' => $user->id , 'id' =>$user_data['account_id'],'status'=>0])->one();
            if(empty($user_account)){
                $data['code'] = '10001';
                $data['msg'] = '提现账户有误,请您核实.';
                return $data;
            }
            //验证用户余额是否足够提现
            if($user->money < $user_data['money_w']){
                $data['code'] = '10001';
                $data['msg'] = '提现金额不能大于您的余额.';
                return $data;
            }
            //计算所需手续费
            $commission_money = $user_data['money_w'] * ($user->commission_fee / 100);
            //计算用户实得金额
            $user_money = $user_data['money_w'] - $commission_money;
            //事务处理
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                //修改用户金额
                $userData = new User();
                $userDatacount = $userData->updateAllCounters(array('money'=>-$user_data['money_w']), 'id=:id', array(':id' => $user->id)); //自动加减余额
                if ($userDatacount <= 0) {
                    $data['code'] = '10001';
                    $msg = '操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
                
                $model->account_id = $user_data['account_id'];
                $model->user_id = $user->id;
                $model->money_w = $user_data['money_w'];
                $model->commission_fee = $user->commission_fee;
                $model->commission_money = $commission_money;
                $model->user_money =$user_money;
                $model->status = 0;
                $model->created_at = time();
                if(!$model->save()){
                    $data['code'] = '10001';
                    $data['msg'] = '操作失败';
                    return $data;
                }
                $transaction->commit();
            } catch (Exception $e) {
                # 回滚事务
                $transaction->rollback();
                $data['code'] = '10001';
                $data['msg'] = $e->getMessage();
                return $data;
            }
        }else{
            $data['code'] = '10001';
            $msg =array_values($model->getFirstErrors())[0];
            $data['msg'] = $msg;
            return $data;
        }
        
        $data['code'] = '200';
        $data['msg'] = '';
        return $data;
    }
    /**
     * 生成订单编码
     */
    private function orderNumber($user)
    {
        $date = date('YmdHis');
        $sms = new SmsLog();
        $code = $sms->rand_string(6,1);
        $userId = str_pad($user->id, 6 , "0", STR_PAD_LEFT);
        
        return $date.$code.$userId;
    }
    
    /**
     * 验证商品订单信息
     */
    private function validateGoodOrder($good_arr, $user, $buyNum)
    {
        //验证商品是否存在
        if (empty($good_arr)) {
            $data['code'] = '10001';
            $data['msg'] = '您购买的商品不存在';
            return $data;
        }
        //验证用户是否购买自己发布的商品
        if ($good_arr->goodMb->user_id == $user->id) {
            $data['code'] = '10001';
            $data['msg'] = '您不能购买自己发布的商品';
            return $data;
        }
        //验证库存是否已售罄
        if ($good_arr->stock_num <= 0) {
            $data['code'] = '10001';
            $data['msg'] = '您购买的商品已售罄';
            return $data;
        }
        //验证商品数量是否大于库存
        if ($buyNum > $good_arr->stock_num) {
            $data['code'] = '10001';
            $data['msg'] = '您购买的商品库存不足';
            return $data;
        }
    }
    
    
    /**
     * 验证用户收获地址
     */
    private function validateOrderAddress($address_arr, $user = 0)
    {
        //验证收获地址是否存在
        if (empty($address_arr)) {
            $data['code'] = '10001';
            $data['msg'] = '您选择的收获地址不存在';
            return $data;
        }
        //验证用户是否选择自己的收获地址
//         if ($address_arr->user_id == $user->id) {
//             $data['code'] = '10001';
//             $data['msg'] = '请选择您自己的收获地址';
//             return $data;
//         }
    }
    
    /**
     * 计算评分
     */
    private function calculateBusinessScore($user_data)
    {
        //商家评分数
        $businessScore = 0;
        //对商品评分
        $goodStar = $user_data['good_star'];
        //对商家评分
        $businessStar = $user_data['business_star'];
        //总评分(四舍五入)
        $totalStar = round(bcdiv(bcadd($goodStar, $businessStar, 2), 2, 2), 0);
        switch ($totalStar) {
            case 1: $businessScore = -2; break;
            case 2: $businessScore = -1; break;
            case 3: $businessScore = 0; break;
            case 4: $businessScore = 1; break;
            case 5: $businessScore = 2; break;
            default: $businessScore = 0; break;
        }
        
        return $businessScore;
    }
    
    /**
     * 计算运费(根据州、收买数量来计算运费)
     */
    private function calculateOrderFare($freight_arr, $address_arr, $buyNum)
    {
        //运费
        $orderFare = 0;
        //州ID
        $stateId = 0;
        //用户默认地址
        if ($address_arr) {
            $stateId = $address_arr->state_id;
        }
        //计算州对应的运费模板
        $stateFreightRule = array();
        //如果有运费规则且有州id
        if ($freight_arr && $stateId) {
            //运费规则
            $freightRules = $freight_arr->freightVars;
            foreach ($freightRules as $rule) {
                //print_r($rule->place_id_arr);
                //如果模版设置了州运费模版且地址所在州在州运费模版中,就回去州运费模版
                if ($rule->place_id_arr && in_array($stateId, explode(',', $rule->place_id_arr))) {
                    $stateFreightRule[] = $rule;
                }
            }
            //print_r($stateFreightRule);exit();
            //如果地址所在州不在州运费模版中,就获取默认运费模版
            if (empty($stateFreightRule)) {
                foreach ($freightRules as $ruleValue) {
                    if ($ruleValue->place_id_arr == 0) {
                        $stateFreightRule[] = $ruleValue;
                    }
                }
            }
        }
        
        //有运费模版,就计算运费
        if ($stateFreightRule) {
            //print_r($stateFreightRule);
            foreach ($stateFreightRule as $stateRule) {
                //如果购买数量小于等于运费模版设置的最初数量,那运费就是最初设置的运费;如果购买数量超出了最初数量,就计算超出数量的运费
                if ($buyNum <= $stateRule->num) {
                    $orderFare = $stateRule->freight;
                } else {
                    //计算每超过一件购买数量需要增加多少运费
                    $exceedFare = bcdiv($stateRule->freightadd, $stateRule->numadd, 2);
                    //计算超过的数量
                    $exceedNum = bcsub($buyNum, $stateRule->num, 0);
                    $orderFare = bcadd($stateRule->freight, bcmul($exceedNum, $exceedFare, 2), 2);
                }
            }
        }
        
        return $orderFare;
    }
    //数组多维转一维 求和
    public function actionArrvalsum($array,$array_key){
        $arr = array();
        foreach($array as $value) {
            $arr[] = $value[$array_key];
        }
        $arr_sum = array_sum($arr);
        return $arr_sum;
    }
}
