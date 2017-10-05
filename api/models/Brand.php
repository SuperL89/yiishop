<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_brand".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_url
 * @property integer $cate_id
 * @property integer $status
 * @property integer $order
 * @property integer $is_hot
 *
 * @property Category $cate
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['cate_id', 'status', 'order', 'is_hot'], 'integer'],
            [['title'], 'string', 'max' => 55],
            [['image_url'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'image_url' => 'Image Url',
            'cate_id' => 'Cate ID',
            'status' => 'Status',
            'order' => 'Order',
            'is_hot' => 'Is Hot',
        ];
    }
    // 明确列出每个字段，适用于你希望数据表或
    // 模型属性修改时不导致你的字段修改（保持后端API兼容性）
    public function fields()
    {
        return [
            'id',
            'title',
            'cate_id',
            'image_url'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
    }
}
