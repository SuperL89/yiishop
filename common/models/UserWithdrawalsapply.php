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
            [['account_id', 'user_id', 'money_w', 'commission_fee', 'commission_money', 'user_money', 'status', 'created_at'], 'required'],
            [['account_id', 'user_id', 'commission_fee', 'status', 'created_at', 'updated_at'], 'integer'],
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
