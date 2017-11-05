<?php

namespace api\models;

use Yii;

class GoodOrderForm extends \yii\db\ActiveRecord
{
    public $mbv_id;
    public $num;
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
        ];
    }
}
