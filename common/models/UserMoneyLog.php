<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_money_log}}".
 *
 * @property integer $id
 * @property integer $admin_uid
 * @property number $new_money
 * @property number $old_money
 * @property integer $created_at
 */
class UserMoneyLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_money_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_uid', 'new_money', 'old_money', 'created_at'], 'required'],
            [['admin_uid', 'created_at'], 'integer'],
            [['new_money', 'old_money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_uid' => '操作者',
            'new_money' => '新余额',
            'old_money' => '旧余额',
            'created_at' => '添加时间',
        ];
    }
}
