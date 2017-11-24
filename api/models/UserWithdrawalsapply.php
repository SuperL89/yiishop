<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%user_withdrawalsapply}}".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $money_w
 * @property integer $commission_fee
 * @property string $commission_money
 * @property string $user_money
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserWithdrawalsapply extends \yii\db\ActiveRecord
{
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
            [['account_id'], 'integer','message' => '提现类型错误.'],
            [['account_id'], 'required','message' => '提现账户不能为空.'],
            //['account_id', 'exist', 'targetClass' => '\api\models\UserAccount', 'message' => '该提现账户不存在.'],
            ['money_w', 'compare', 'compareValue' => 0, 'operator' => '>','message' => '提现金额不能小于等于零.'],
            [['money_w'], 'required','message' => '提现金额不能为空.'],
            [['money_w'], 'number','message' => '提现金额类型错误.'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'user_id' => 'User ID',
            'money_w' => 'Money W',
            'commission_fee' => 'Commission Fee',
            'commission_money' => 'Commission Money',
            'user_money' => 'User Money',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
