<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_place".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parentid
 * @property integer $status
 * @property integer $order
 *
 * @property GoodMb[] $goodMbs
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%place}}';
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodMbs()
    {
        return $this->hasMany(GoodMb::className(), ['place_id' => 'id']);
    }
}
