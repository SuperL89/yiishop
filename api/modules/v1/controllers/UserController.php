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

class UserController extends ActiveController
{
    public $modelClass = 'api\models\User';
    
    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'register' => ['POST'],
            'sendverifycode' => ['POST'], 
            'resetpwd' => ['POST'],
        ];
    }
    
    public function behaviors() 
    {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className(),
                    'optional' => [
                        'login',
                        'signup-test',
                        'register',
                        'send-verifycode',
                        'resetpwd',
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
                $msg =$model->errors;
                $data['msg'] = $msg;
                return $data;
            }
        } else {
            $data['code'] = '10001';
            $msg =$model->errors;
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
        //print_r(Yii::$app->request->post());exit();
        if (Yii::$app->request->post() && $model->validate()) {
            if($user = $model->signup()){
                $data['code'] = '200';
                $data['msg'] = '';
                $data['data']['userid'] = $user->id;
                return $data;
            }
        }else {
            $data['code'] = '10002';
            $msg =$model->errors;
            $data['msg'] = $msg;
            return $data;
        } 
    }
    
    /*重置密码*/
    public function actionResetpwd()
    {
        $model = new ResetpwdForm();
        $model->setAttributes(Yii::$app->request->post());
        //print_r(Yii::$app->request->post());exit();
        if (Yii::$app->request->post() && $model->validate()) {
                if($user = $model->resetPassword()){
                    $data['code'] = '200';
                    $data['msg'] = '';
                    return $data;
                }
            }else {
                $data['code'] = '10002';
                $msg =$model->errors;
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
            Yii::$app->smser->send($phone , '【合心意】您的验证码是'.$code1);
        }else{
                $data['code'] = '10004';
                $data['msg'] = '验证码发送失败';
                return $data;
        }
        $data['code'] = '200';
        $data['msg'] = '';
        return $data;
    }
}
