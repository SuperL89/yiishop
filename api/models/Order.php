<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $order_num
 * @property integer $user_id
 * @property integer $business_id
 * @property integer $good_id
 * @property integer $mb_id
 * @property integer $mbv_id
 * @property string $user_address
 * @property integer $pay_type
 * @property string $good_price
 * @property integer $pay_num
 * @property string $good_total_price
 * @property string $order_fare
 * @property string $order_total_price
 * @property string $express_name
 * @property string $express_num
 * @property integer $status
 * @property integer $created_at
 * @property integer $pay_at
 * @property integer $deliver_at
 * @property integer $complete_at
 * @property string $good_var
 * @property string $cancel_text
 * @property string $message
 *
 * @property User $user
 * @property User $business
 * @property Good $good
 * @property GoodMb $mb
 * @property GoodMbv $mbv
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_num', 'user_id', 'business_id', 'good_id', 'mb_id', 'mbv_id', 'user_address', 'good_price', 'pay_num', 'good_total_price', 'order_fare', 'order_total_price', 'created_at'], 'required'],
            [['user_id', 'business_id', 'good_id', 'mb_id', 'mbv_id', 'pay_type', 'pay_num', 'status', 'created_at', 'pay_at', 'deliver_at', 'complete_at'], 'integer'],
            [['good_price', 'good_total_price', 'order_fare', 'order_total_price'], 'number'],
            [['order_num'], 'string', 'max' => 128],
            [['user_address', 'good_var'], 'string', 'max' => 1000],
            [['express_name', 'express_num'], 'string', 'max' => 50],
            [['cancel_text'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 300],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['business_id' => 'id']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::className(), 'targetAttribute' => ['good_id' => 'id']],
            [['mb_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMb::className(), 'targetAttribute' => ['mb_id' => 'id']],
            [['mbv_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMbv::className(), 'targetAttribute' => ['mbv_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_num' => 'Order Num',
            'user_id' => 'User ID',
            'business_id' => 'Business ID',
            'good_id' => 'Good ID',
            'mb_id' => 'Mb ID',
            'mbv_id' => 'Mbv ID',
            'user_address' => 'User Address',
            'pay_type' => 'Pay Type',
            'good_price' => 'Good Price',
            'pay_num' => 'Pay Num',
            'good_total_price' => 'Good Total Price',
            'order_fare' => 'Order Fare',
            'order_total_price' => 'Order Total Price',
            'express_name' => 'Express Name',
            'express_num' => 'Express Num',
            'status' => 'Status',
            'created_at' => 'Created At',
            'pay_at' => 'Pay At',
            'deliver_at' => 'Deliver At',
            'complete_at' => 'Complete At',
            'good_var' => 'Good Var',
            'message' => 'Message',
            'cancel_text' => 'cancel Text',
            'library_at' => '出库时间',
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
    public function getBusiness()
    {
        return $this->hasOne(User::className(), ['id' => 'business_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(Good::className(), ['id' => 'good_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMb()
    {
        return $this->hasOne(GoodMb::className(), ['id' => 'mb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMbv()
    {
        return $this->hasOne(GoodMbv::className(), ['id' => 'mbv_id']);
    }
}
