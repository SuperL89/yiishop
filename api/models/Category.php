<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parentid
 * @property integer $status
 * @property integer $order
 *
 * @property Brand[] $brands
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parentid', 'status', 'order'], 'integer'],
            [['title'], 'string', 'max' => 50],
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
            'parentid' => 'Parentid',
            'status' => 'Status',
            'order' => 'Order',
        ];
    }
    // 明确列出每个字段，适用于你希望数据表或
    // 模型属性修改时不导致你的字段修改（保持后端API兼容性）
    public function fields()
    {
        return [
            'id',
            'title',
            'parentid'
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrands()
    {
        return $this->hasMany(Brand::className(), ['cate_id' => 'id']);
    }
}
