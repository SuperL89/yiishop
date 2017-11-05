<?php

namespace api\models;

use Yii;

class GetGoodOrderForm extends \yii\db\ActiveRecord
{
    public $mbv_id;
    public $num;
    public $address_id;
    public $message;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['mbv_id', 'required', 'message' => '商品属性id不能为空.'],
            ['mbv_id', 'integer', 'message' => '商品属性id类型错误.'],
            [['mbv_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMbv::className(), 'targetAttribute' => ['mbv_id' => 'id'],'message' => '无此商品信息.'],
            ['num', 'required', 'message' => '购买数量不能为空.'],
            ['num', 'integer', 'message' => '购买数量类型错误.'],
            ['num', 'compare', 'compareValue' => 0, 'operator' => '>','message' => '购买数量不得小于或等于零.'],
            ['address_id', 'required', 'message' => '收货地址id不能为空.'],
            ['address_id', 'integer', 'message' => '收货地址id类型错误.'],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['address_id' => 'id'],'message' => '收货地址不存在.'],
            [['message'], 'string', 'max' => 300,'message' => '留言不能超过100字.'],
        ];
    }
}
