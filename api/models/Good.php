<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_good".
 *
 * @property integer $id
 * @property integer $good_num
 * @property string $title
 * @property string $description
 * @property integer $cate_id
 * @property integer $brand_id
 * @property integer $status
 * @property integer $is_reco
 * @property integer $is_hot
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $order
 *
 * @property Brand $brand
 * @property Category $cate
 * @property GoodClicks[] $goodClicks
 * @property GoodImage[] $goodImages
 */
class Good extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_num', 'title', 'description', 'cate_id', 'brand_id', 'created_at', 'updated_at'], 'required'],
            [['good_num', 'cate_id', 'brand_id', 'status', 'is_reco', 'is_hot', 'created_at', 'updated_at', 'user_id', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_num' => 'Good Num',
            'title' => 'Title',
            'description' => 'Description',
            'cate_id' => 'Cate ID',
            'brand_id' => 'Brand ID',
            'status' => 'Status',
            'is_reco' => 'Is Reco',
            'is_hot' => 'Is Hot',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'order' => 'Order',
        ];
    }

/**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCates()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id'])->select('title');
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodClicks()
    {
        return $this->hasOne(GoodClicks::className(), ['good_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodImages()
    {
        return $this->hasMany(GoodImage::className(), ['id' => 'good_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodImage()
    {
        return $this->hasOne(GoodImage::className(), ['good_id' => 'id']);
    }
    
    public function getGoodMb()
    {
        return $this->hasMany(GoodMb::className(), ['good_id' => 'id']);
    }
    public function getGoodCode()
    {
        return $this->hasMany(GoodCode::className(), ['good_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    // 明确列出每个字段，适用于你希望数据表或
    // 模型属性修改时不导致你的字段修改（保持后端API兼容性）
//     public function fields()
//     {
//         return [
//             'id',
//             'good_num',
//             'title',
//             'description'
//         ];
//     }
    // 过滤掉一些字段，适用于你希望继承
    // 父类实现同时你想屏蔽掉一些敏感字段
//         public function fields()
//         {
//             $fields = parent::fields();
        
//             // 删除一些包含敏感信息的字段
//             unset($fields['id'],$fields['created_at'], $fields['updated_at'], $fields['status']);
        
//             return $fields;
//         }
}
