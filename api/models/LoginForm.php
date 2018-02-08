<?php
namespace api\models;

//use Yii;
use yii\base\Model;
use api\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user;

    const GET_API_TOKEN = 'generate_api_token';

    public function init ()
    {
        parent::init();
        $this->on(self::GET_API_TOKEN, [$this, 'onGenerateApiToken']);
    }


    /**
     * @inheritdoc
     * 对客户端表单数据进行验证的rule
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => '用户名不能为空.'],
            //国内手机号验证规则
            //['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'请输入正确的手机号/用户名'],
            //美国手机验证规则
            ['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'请输入正确的手机号/用户名'],
            
            ['password', 'required', 'message' => '用户名不能为空.'],
            ['password', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/','message'=>'6-12位密码必须由字母+数字组成'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * 自定义的密码认证方法
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = $this->getUser();
            if (!$this->_user || !$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_API_TOKEN);
            return $this->_user;
        } else {
            return null;
        }
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
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateApiToken ()
    {
        if (!User::apiTokenIsValid($this->_user->api_token)) {
            $this->_user->generateApiToken();
            $this->_user->save(false);
        }
    }
}