<?php
namespace api\models;

use yii\base\Model;
use api\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $username;
    public $password_old;
    public $password;
    public $password_repeat;
    
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
            //['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'请输入正确的手机号/用户名'],
            //美国手机验证规则
            ['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'非美国手机用户不能通过'],
            ['password_old', 'required','message' => '旧密码不能为空.'],
            ['password_old', 'trim'],
            ['password_old', 'validatePassword'],
            
            ['password', 'required','message' => '密码不能为空.'],
            ['password', 'trim'],
            ['password', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            
            ['password_repeat', 'required','message' => '重复密码不能为空.'],
            ['password_repeat', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            ['password_repeat', 'compare','compareAttribute'=>'password','message'=>'两次输入密码不一致.'],
        ];
    }
    
    /**
     * 自定义的密码认证方法
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = $this->getUser();
            if (!$this->_user || !$this->_user->validatePassword($this->password_old)) {
                $this->addError($attribute, '旧密码密码错误.');
            }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '手机号/用户名',
            'verifycode' => '验证码',
            'password_old' => '旧密码',
            'password' => '密码',
            'password_repeat' => '重复密码',
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
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
