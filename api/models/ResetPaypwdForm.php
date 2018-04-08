<?php
namespace api\models;

use Yii;
use yii\base\Model;
use api\models\User;

/**
 * Password reset form
 */
class ResetPaypwdForm extends Model
{
    public $username;
    public $pay_pw;
    public $pay_pw_old;
    
    private $_user;

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
            ['pay_pw_old', 'required','message' => '旧密码不能为空.'],
            ['pay_pw_old', 'trim'],
            ['pay_pw_old', 'validatePassword'],
            
            ['pay_pw', 'required','message' => '密码不能为空.'],
            ['pay_pw', 'trim'],
            ['pay_pw', 'match', 'pattern'=>'/^\d{6}$/','message'=>'密码必须由6位纯数字组成'],
            
        ];
    }
    
    /**
     * 自定义的密码认证方法
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = $this->getUser();
            if (!$this->_user || !$this->_user->validatePayPassword($this->pay_pw_old)) {
                $this->addError($attribute, '旧密码密码错误.');
            }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '手机号/用户名',
            'pay_pw_old' => '旧密码',
            'pay_pw' => '密码',
        ];
    }

    /**
     * 根据用户名获取用户的认证信息
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
    
        return $this->_user;
    }
    
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function ResetPaypwd()
    {
        
        $user = $this->_user;
        //print_r($user);exit();
        $pay_pw = Yii::$app->security->generatePasswordHash($this->pay_pw);
        $user->pay_pw = $pay_pw;
        $user->updated_at = time();
        return $user->save() ? true : false;
    }
}
