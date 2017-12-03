<?php
namespace api\models;

use yii\base\Model;
use api\models\User;
//use yii\helpers\VarDumper;
/**
 * Signup form
 */
class ResetpwdForm extends Model
{
    const  VERCODE_USAGE = 'resetpwd';
    public $username;
    public $password;
    public $verifycode;

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
            //['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'请输入正确的手机号/用户名'],
            //美国手机验证规则
            //['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'非美国手机用户不能通过'],
            //['username', 'string', 'min' => 2, 'max' => 255],
            
            //验证码
            ['verifycode', 'trim'],
            ['verifycode', 'required', 'message' => '验证码不能为空.'],
            ['verifycode', '\common\validators\SmscodeValidator', 'usage' => self::VERCODE_USAGE ],
            
            ['password', 'trim'],
            ['password', 'required','message' => '密码不能为空.'],
            ['password', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'verifycode' => '验证码',
            'password' => '密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function resetpwd()
    {
        $user = User::find()->select(['id'])->where(['username' => $this->username])->one();
        $user->setPassword($this->password);
        $user->removePasswordResetToken();   
        return $user->save() ? true : false;
    }
}
