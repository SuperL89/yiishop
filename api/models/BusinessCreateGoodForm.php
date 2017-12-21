<?php

namespace api\models;

use Yii;

class BusinessCreateGoodForm extends \yii\db\ActiveRecord
{
    public $image_url;
    public $title;
    public $description;//商品描述
    public $good_num;//商品编码
    public $cate_id;//商品分类
    public $brand_id;//商品品牌
    public $place_id;//发货地
    public $freight_id;//运费模版
    public $address_id;//仓库
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['good_num', 'trim'],
            ['cate_id', 'trim'],
            ['brand_id', 'trim'],
            //['freight_id', 'trim'],
            
            ['image_url', 'required', 'message' => '商品图片不能为空.'],
            ['title', 'required', 'message' => '商品标题不能为空.'],
            ['description', 'required', 'message' => '商品详细不能为空.'],  
            ['good_num', 'required', 'message' => '商品编码不能为空.'],
            
            ['cate_id', 'required', 'message' => '商品分类不能为空.'],
            ['cate_id', 'integer','message' => '商品分类类型不正确.'],
            
            ['brand_id', 'required', 'message' => '商品品牌不能为空.'],
            ['brand_id', 'integer','message' => '商品品牌类型不正确.'],
            
            ['brand_id', 'required', 'message' => '发货地不能为空.'],
            ['brand_id', 'integer','message' => '发货地类型不正确.'],
            
//             ['freight_id', 'required', 'message' => '运费模版不能为空.'],
//             ['freight_id', 'integer','message' => '运费模版类型不正确.'],
            ['address_id', 'required', 'message' => '仓库不能为空.'],
            ['address_id', 'integer','message' => '仓库类型不正确.'],
            
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id'],'message' => '商品分类不存在.'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id'],'message' => '商品品牌不存在.'],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id'],'message' => '发货地不存在.'],
            //[['freight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Freight::className(), 'targetAttribute' => ['freight_id' => 'id'],'message' => '运费模版不存在.'],
        ];
    }
}
