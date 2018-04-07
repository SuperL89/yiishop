<?php
namespace api\models;

use Yii;
use yii\base\Model;
use api\models\User;
use yii\helpers\VarDumper;

/**
 * Signup form
 */
class SignupForm extends Model
{
    const  VERCODE_USAGE = 'register';
    public $username;
    public $verifycode;
    public $password;
    public $nickname;
    public $sex;
    public $image_h;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => '手机号/用户名不能为空.'],
            ['username', 'unique', 'targetClass' => '\api\models\User', 'message' => '手机号/用户名已存在.'],
            //国内手机号验证规则
            //['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'请输入正确的手机号/用户名'],
            //美国手机验证规则
//             ['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'请输入正确的手机号/用户名'],
            ['username','match','pattern'=>'/^((?:\d{9})|(?:\d{3}-\d{4})|(?:\d{3}-\d{3}-\d{4}))$/','message'=>'请输入正确的手机号/用户名'],
            //验证码
            ['verifycode', 'trim'],
            ['verifycode', 'required', 'message' => '验证码不能为空.'],
            ['verifycode', '\common\validators\SmscodeValidator', 'usage' => self::VERCODE_USAGE ],
            
            ['password', 'trim'],
            ['password', 'required', 'message' => '密码不能为空.'],
            ['password', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            
            //用户信息
            ['nickname', 'trim'],
            ['sex', 'trim'],
            ['image_h', 'trim'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifycode' => '验证码',
            'nickname' => '昵称',
            'sex' => '性别',
            'image_h'=>'头像',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        $user = new User();
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->sex = $this->sex ?: 2;
        $user->image_h = $this->image_h;
        $user->created_at = time();
        $user->updated_at = time();
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->save(); VarDumper::dump($user->errors);exit();
        return $user->save() ? $user : null;
    }
}
