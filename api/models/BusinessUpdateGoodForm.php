<?php

namespace api\models;

use Yii;

class BusinessUpdateGoodForm extends \yii\db\ActiveRecord
{
    //public $freight_id;//运费模版
    public $place_id;//运费模版
    public $address_id;//仓库    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
//             ['freight_id', 'trim'],
            
//             ['freight_id', 'required', 'message' => '运费模版不能为空.'],
//             ['freight_id', 'integer','message' => '运费模版类型不正确.'],
            ['address_id', 'trim'],

            ['address_id', 'required', 'message' => '仓库不能为空.'],
            ['address_id', 'integer','message' => '仓库类型不正确.'], 
            
            ['place_id', 'trim'],
            
            ['place_id', 'required', 'message' => '发货地不能为空.'],
            ['place_id', 'integer','message' => '发货地类型不正确.'],      
            
        ];
    }
}
