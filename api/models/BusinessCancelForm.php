<?php

namespace api\models;

use Yii;

class BusinessCancelForm extends \yii\db\ActiveRecord
{
    public $order_id;
    public $cancel_msg;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
            ['cancel_msg', 'required', 'message' => '取消原因不能为空.'],
        ];
    }
}
