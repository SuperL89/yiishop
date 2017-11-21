<?php
namespace common\models;

use yii\base\Model;
use common\models\User;


class ResetpwdForm extends Model
{
    public $password;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            ['password', 'required','message' => '密码不能为空.'],
            ['password', 'trim'],
            ['password', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            
            ['password_repeat', 'required','message' => '重复密码不能为空.'],
            ['password_repeat', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            ['password_repeat', 'compare','compareAttribute'=>'password','message'=>'两次输入密码不一致.'],
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'password_repeat' => '重复密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function resetPassword($id)
    {

        $user = User::findOne($id);
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
       
        return $user->save() ? true : false;
    }
}
