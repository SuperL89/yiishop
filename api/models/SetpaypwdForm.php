<?php
namespace api\models;

use Yii;
use yii\base\Model;
use api\models\User;
//use yii\helpers\VarDumper;
/**
 * Signup form
 */
class SetpaypwdForm extends Model
{
    const  VERCODE_USAGE = 'setpaypwd';
    public $username;
    public $pay_pw;
    public $verifycode;
    public $pay_pw_repeat;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            ['username', 'trim'],
            ['username', 'required','message' => '手机号/用户名不能为空.'],
            ['username', 'exist', 'targetClass' => '\api\models\User', 'message' => '该用户不存在.'],
            //国内手机号验证规则
            //['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'手机号/用户名不符合规则'],
            //美国手机验证规则
            ['username','match','pattern'=>'/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/','message'=>'非美国手机用户不能通过'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            //验证码
            ['verifycode', 'trim'],
            ['verifycode', 'required', 'message' => '验证码不能为空.'],
            ['verifycode', '\common\validators\SmscodeValidator', 'usage' => self::VERCODE_USAGE ],
            
            ['pay_pw', 'trim'],
            ['pay_pw', 'required','message' => '密码不能为空.'],
            ['pay_pw', 'match', 'pattern'=>'/^\d{6}$/','message'=>'密码必须由6位纯数字组成'],
            
            ['pay_pw_repeat', 'required','message' => '重复密码不能为空.'],
            ['pay_pw_repeat', 'match', 'pattern'=>'/^\d{6}$/','message'=>'密码必须由6位纯数字组成'],
            ['pay_pw_repeat', 'compare','compareAttribute'=>'pay_pw','message'=>'两次输入密码不一致.'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'verifycode' => '验证码',
            'pay_pw' => '支付密码',
            'pay_pw_repeat' => '重复支付密码',
        ];
    }

    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function setpaypwd()
    {
        $user = User::find()->select(['id'])->where(['username' => $this->username])->one();
        $pay_pw = Yii::$app->security->generatePasswordHash($this->pay_pw);
        $user->pay_pw = $pay_pw;
        $user->updated_at = time();
        return $user->save() ? true : false;
    }
}
