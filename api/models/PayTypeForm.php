<?php

namespace api\models;

use Yii;

class PayTypeForm extends \yii\db\ActiveRecord
{
    public $order_id;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
        ];
    }
}
