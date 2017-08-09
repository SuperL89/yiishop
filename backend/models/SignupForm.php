<?php
namespace backend\models;

use yii\base\Model;
use backend\models\AdminUser;
//use yii\helpers\VarDumper;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $nickname;
    public $email;
    //public $role;
    //public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\AdminUser', 'message' => '用户名已存在.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\AdminUser', 'message' => '邮箱地址已存在.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            
            ['password_repeat', 'compare','compareAttribute'=>'password','message'=>'两次输入密码不一致.'],
            
            ['nickname', 'required'],
            ['nickname', 'string', 'min' => 2, 'max' => 255],
            
            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'password_repeat' => '重复密码',
            'nickname' => '昵称',
            'email' => '邮箱',
            //'role' => '权限',
            //'status' => '状态',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
//         if (!$this->validate()) {
//             return null;
//         }
        
        $user = new AdminUser();
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->email = $this->email;
        $user->login_at = '0';
        $user->created_at = time();
        $user->updated_at = time();
        //$user->role = $this->role;
        //$user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->save(); VarDumper::dump($user->errors);exit();
        return $user->save() ? $user : null;
    }
}
