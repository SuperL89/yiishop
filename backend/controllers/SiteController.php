<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminLoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha','request-password-reset','reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',                
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,//调整验证码是数字还是中文                
                // 'fixedVerifyCode' => substr(time(),mt_rand(1,9),5),                
                'backColor'=>0xffffff,//背景颜色                
                'maxLength' => 4, //最大显示个数                
                'minLength' => 4,//最少显示个数                
                'padding' => 5,//间距              
                'height'=>40,//高度              
                'width' => 100,  //宽度                
                'foreColor'=>0x0000000,     //字体颜色               
                'offset'=>4,        //设置字符偏移量 有效果     
            ],  
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        //return Yii::$app->request->userIP;exit();
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new AdminLoginForm();
        if (Yii::$app->session['login_error_times'] >= $model->errorMaxTimes) {
            $model->scenario = 'loginError';
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->remove('login_error_times');
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        //return Yii::$app->request->userIP;exit();
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '发送成功！请查收您的邮件。');
                
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '发送失败请检查您的邮箱地址！');
            }
        }
        
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            
            return $this->goHome();
        }
        
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
