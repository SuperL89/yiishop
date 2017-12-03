<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "{{%user_withdrawalsapply}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $user_id
 * @property string $money_w
 * @property integer $commission_fee
 * @property string $commission_money
 * @property string $user_money
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $complete_at
 */
class UserWithdrawalsapply extends \yii\db\ActiveRecord
{ 
    public $complete_at;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_withdrawalsapply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'user_id', 'money_w', 'commission_fee', 'commission_money', 'user_money', 'status', 'created_at'], 'required'],
            [['account_id', 'user_id', 'commission_fee', 'status', 'created_at', 'updated_at', 'complete_at'], 'integer'],
            [['money_w'], 'number'],
            [['commission_money', 'user_money'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => '提现账户id',
            'user_id' => '用户id',
            'money_w' => '提现金额',
            'commission_fee' => '手续费比例',
            'commission_money' => '手续费金额',
            'user_money' => '用户实得',
            'status' => '审核状态',
            'created_at' => '创建时间',
            'updated_at' => '审核时间',
            'complete_at' => '完成时间',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(), ['id' => 'account_id']);
    }
    
    const STATUS_DELETED = 3;
    const STATUS_SUCCESS= 2;
    const STATUS_ACTIVE = 1;
    const STATUS_ACTIVEING = 0;
    /**
     * 设置提现申请状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVEING=>'待审核',self::STATUS_ACTIVE=>'审核通过',self::STATUS_SUCCESS=>'提现完成',self::STATUS_DELETED=>'审核拒绝'];
    }
    /**
     * 获得提现申请并转为中文显示
     */
    public function getStatusStr()
    {
        if($this->status==self::STATUS_ACTIVE){
            return '审核通过';
        }elseif ($this->status==self::STATUS_SUCCESS){
            return '提现完成';
        }elseif ($this->status==self::STATUS_ACTIVEING){
            return '待审核';
        }elseif($this->status==self::STATUS_DELETED){
            return '审核拒绝';
        }else{
            return '未知';
        }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
    
//     const ALIPAY = 1;
//     const BANK = 2;
    
//     /**
//      * 设置提现申请状态显示常量
//      */
//     public static function allType()
//     {
//         return [self::ALIPAY=>'支付宝',self::BANK=>'银行卡'];
//     }
//     /**
//      * 获得提现申请并转为中文显示
//      */
//     public function getTypeStr()
//     {
//         if($this->type==self::ALIPAY){
//             return '支付宝';
//         }elseif ($this->type==self::BANK){
//             return '银行卡';
//         }else{
//             return '未知';
//         }
//         //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
//     }
    
}
