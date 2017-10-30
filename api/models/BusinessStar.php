<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%business_star}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $business_id
 * @property integer $order_id
 * @property integer $good_star
 * @property integer $business_star
 * @property integer $created_at
 */
class BusinessStar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_star}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'business_id', 'order_id', 'good_star', 'business_star', 'created_at'], 'required'],
            [['user_id', 'business_id', 'order_id', 'good_star', 'business_star', 'created_at'], 'integer'],
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
            'business_id' => 'Business ID',
            'order_id' => 'Order ID',
            'good_star' => 'Good Star',
            'business_star' => 'Business Star',
            'created_at' => 'Created At',
        ];
    }
}
