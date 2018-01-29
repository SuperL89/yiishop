<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $api_token
 * @property integer $login_at
 * @property string $login_ip
 * @property string $nickname
 * @property integer $sex
 * @property string $image_h
 * @property string $money
 * @property string $pay_pw
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    const USER_MAN = 1;
    const USER_WOMAN = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            
            ['commission_fee', 'default', 'value' => 5],
            
            //用户信息
            ['image_h', 'trim'],
            ['username', 'trim'],
            ['nickname', 'trim'],
            ['sex', 'trim'],
            ['money', 'trim'],
        ];
    }
    public function attributeLabels()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            
            ['commission_fee', 'default', 'value' => 5],
            
            'id' => 'ID',
            'username' => '用户名/手机号',
//             'auth_key' => 'Auth Key',
//             'password_hash' => 'Password Hash',
//             'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            //'role' => 'Role',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
            'api_token' => 'Api Token',
            'login_at' => '最后登陆时间',
            'login_ip' => '登录ip',
            'nickname' => '昵称',
            'sex' => '性别',
            'image_h' => '头像',
            'money' => '余额',
            'commission_fee' => '提现手续费(%)',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * 设置用户状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已禁用'];
    }
    /**
     * 获得用户状态并转为中文显示
     */
    public function getStatusStr()
    {
        return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
    /**
     * 设置用户性别显示常量
     */
    public static function allSex()
    {
        return [self::USER_WOMAN=>'女',self::USER_MAN=>'男'];
    }
    /**
     * 获得用户性别并转为中文显示
     */
    public function getSexStr()
    {
        return $this->sex==self::USER_WOMAN?'女':'男';
    }
}
