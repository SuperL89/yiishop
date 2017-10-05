<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_banner".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_url
 * @property string $ad_url
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'image_url'], 'required'],
            [['created_at', 'updated_at', 'status','order'], 'integer'],
            [['title', 'image_url', 'ad_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'image_url' => 'Image Url',
            'ad_url' => 'Ad Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'order' => 'Order',
        ];
    }
    // 明确列出每个字段，适用于你希望数据表或
    // 模型属性修改时不导致你的字段修改（保持后端API兼容性）
    public function fields()
    {
        return [
                'title',
                'image_url',
                'ad_url'
            ];
    }
    
    // 过滤掉一些字段，适用于你希望继承
    // 父类实现同时你想屏蔽掉一些敏感字段
//     public function fields()
//     {
//         $fields = parent::fields();
    
//         // 删除一些包含敏感信息的字段
//         unset($fields['id'],$fields['created_at'], $fields['updated_at'], $fields['status']);
    
//         return $fields;
//     }
}
