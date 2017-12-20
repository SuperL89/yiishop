<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_tradelog}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $money
 * @property string $order_num
 * @property integer $created_at
 */
class UserTradelog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_tradelog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'money', 'order_num', 'created_at'], 'required'],
            [['type', 'created_at'], 'integer'],
            [['money'], 'number'],
            [['order_num'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'money' => 'Money',
            'order_num' => 'Order Num',
            'created_at' => 'Created At',
        ];
    }
}
