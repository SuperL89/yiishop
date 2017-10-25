<?php

namespace api\modules\v1\controllers;

use Yii;
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
use api\models\GoodMbv;
use api\models\Freight;


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
                        'signup-test',
                        'register',
                        'send-verifycode',
                        'resetpwd',
                        'get-place',
                        'get-category'
                    ],
                ] 
        ] );
    }
    public function actions()
    {
        $action =  parent::actions(); 
        unset($action['index'],$action['view'],$action['create'],$action['update'],$action['delete']); //所有动作删除
    }

    /**
     * 添加测试用户
     */
    public function actionSignupTest()
    {
        $user = new User();
        $user->generateAuthKey();
        $user->setPassword('qq123456');
        $user->username = '187671929281';
        $user->email = '111@111.com';
        $user->save(false);
    
        return [
            'code' => 0
        ];
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
        }else{
                $data['code'] = '10004';
                $data['msg'] = '验证码发送失败';
                return $data;
        }
        $data['code'] = '200';
        $data['msg'] = '';
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
                $data['data']['username'] = $user->username;
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
        $address_arr = UserAddress::find()->select(['*'])->where(['user_id'=>$user->id,'status'=>[0,1]])->filterWhere(['user_id'=>$user->id,'status'=>[0,1],'id'=>$address_id])->with([
            'city' => function ($query) {
               $query->select('id,title,parentid')->andWhere('status = 0')->with([
                   'state' => function ($query) {
                        $query->select('id,title,parentid')->andWhere('status = 0')->with([
                            'country' => function ($query) {
                                $query->select('id,title,parentid')->andWhere('status = 0');
                            }
                        ]);
                   }
               ]);
            }
        ])
        ->asArray()
        //->createCommand()->getRawSql();exit();
        ->all();

        if($address_arr){
            foreach ($address_arr as $k => $v){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data'][$k]['id'] = $v['id'];
                $data['data'][$k]['name'] = $v['name'];
                $data['data'][$k]['country_id'] = $v['city']['state']['country']['id'];
                $data['data'][$k]['country_name'] = $v['city']['state']['country']['title'];
                $data['data'][$k]['state_id'] = $v['city']['state']['id'];
                $data['data'][$k]['state_name'] = $v['city']['state']['title'];
                $data['data'][$k]['city_id'] = $v['city_id'];
                $data['data'][$k]['city_name'] = $v['city']['title'];
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
         $place_id = isset($user_data['place_id']) ? $user_data['place_id'] : NULL;
         $place_arr = Place::find()
         ->select(['id','title','parentid'])->where(['parentid'=>$place_id,'status'=> 0])
         ->asArray()
         ->all();
        $data['code'] = '200';
        $data['msg'] = '';
        $data['data'] = $place_arr;
        return $data;
    }
    /**
     * 获取分类表
     */
    public function actionGetCategory()
    {
        $user_data = Yii::$app->request->post();
        $category_id = isset($user_data['category_id']) ? $user_data['category_id'] : NULL;
        $category_arr = Category::find()
        ->select(['id','title','parentid'])->where(['parentid'=>$category_id,'status'=> 0])
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
            $business=$model->find()->select(['id'])->where(['status'=>0,'user_id'=>$user->id])->one();
            if($business){
                $business->user_id = $user->id;
                $business->image_url = $user_data['image_url'];
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
                    $msg ='操作失败';
                    $data['msg'] = $msg;
                    return $data;
                }
            }else{
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
    
}
