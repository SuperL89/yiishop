<?php

namespace common\models;

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
            [['user_id', 'type', 'account', 'realname', 'status', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['account', 'realname'], 'string', 'max' => 50],
            [['account_bank'], 'string', 'max' => 100],
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
    
    const ALIPAY = 1;
    const BANK = 2;
    
    /**
     * 设置提现申请状态显示常量
     */
    public static function allType()
    {
        return [self::ALIPAY=>'支付宝',self::BANK=>'银行卡'];
    }
    /**
     * 获得提现申请并转为中文显示
     */
    public function getTypeStr()
    {
        if($this->type==self::ALIPAY){
            return '支付宝';
        }elseif ($this->type==self::BANK){
            return '银行卡';
        }else{
            return '未知';
        }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
}
