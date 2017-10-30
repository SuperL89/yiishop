<?php

namespace api\models;

use Yii;

class GoodOrderForm extends \yii\db\ActiveRecord
{
    public $goodmbv_id;
    public $num;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['goodmbv_id', 'required', 'message' => '商品属性id不能为空.'],
            ['goodmbv_id', 'integer', 'message' => '商品属性id类型错误.'],
            [['goodmbv_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMbv::className(), 'targetAttribute' => ['goodmbv_id' => 'id'],'message' => '商品不存在.'],
            ['num', 'required', 'message' => '购买数量不能为空.'],
            ['num', 'integer', 'message' => '购买数量类型错误.'],
            ['num', 'compare', 'compareValue' => 0, 'operator' => '>','message' => '购买数量不得小于或等于零.'],
        ];
    }
}
