<?php

namespace api\models;

use Yii;

class BusinessDeliveryForm extends \yii\db\ActiveRecord
{
    public $order_id;
    public $express_id;
    public $express_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
            ['express_id', 'required', 'message' => '快递id不能为空.'],
            [['express_id'], 'exist', 'skipOnError' => true, 'targetClass' => Express::className(), 'targetAttribute' => ['express_id' => 'id'],'message' => '快递公司不存在.'],
            ['express_number', 'required', 'message' => '快递编号不能为空.'],
        ];
    }
}
