<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%user_account}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $account
 * @property string $realname
 * @property string $account_bank
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserAccount extends \yii\db\ActiveRecord
{
    const  VERCODE_USAGE = 'account';
    public $verifycode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['type', 'account', 'realname', 'status', 'created_at', 'updated_at'], 'required'],
            ['type', 'in', 'range' => [1, 2], 'message' => '提现账户类型错误.'],
            [['type'], 'integer','message' => '提现账户类型错误.'],
            ['type', 'required','message' => '提现账户类型不能为空.'],
            ['account', 'required','message' => '提现账户不能为空.'],
            ['account', 'unique','message' => '提现账户已存在.'],
            ['realname', 'required','message' => '姓名不能为空.'],
            [['account', 'realname'], 'string', 'max' => 50],
            [['account_bank'], 'string', 'max' => 100],
            
            //验证码
            ['verifycode', 'trim'],
            ['verifycode', 'required', 'message' => '验证码不能为空.'],
            ['verifycode', '\common\validators\SmscodeValidator', 'usage' => self::VERCODE_USAGE ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'account' => 'Account',
            'realname' => 'Realname',
            'account_bank' => 'Account Bank',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
