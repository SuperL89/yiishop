<?php

namespace api\models;

use Yii;

class ConfirmReceiptForm extends \yii\db\ActiveRecord
{
    public $order_id;
    public $business_star;
    public $good_star;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
            ['business_star', 'required', 'message' => '对商家的评分不能为空.'],
            ['business_star', 'integer', 'message' => '对商家的评分类型错误.'],
            ['business_star', 'compare', 'compareValue' => 0, 'operator' => '>','message' => '对商家的评分不得小于或等于零.'],
            ['business_star', 'compare', 'compareValue' => 5, 'operator' => '<=','message' => '对商家的评分不得大于五.'],
            ['good_star', 'required', 'message' => '对商品的评分不能为空.'],
            ['good_star', 'integer', 'message' => '对商品的评分类型错误.'],
            ['good_star', 'compare', 'compareValue' => 0, 'operator' => '>','message' => '对商品的评分不得小于或等于零.'],
            ['good_star', 'compare', 'compareValue' => 5, 'operator' => '<=','message' => '对商品的评分不得大于五.'],
        ];
    }
}
