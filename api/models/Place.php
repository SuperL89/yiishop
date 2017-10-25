<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%place}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parentid
 * @property integer $status
 * @property integer $order
 *
 * @property GoodMb[] $goodMbs
 * @property Place $parent
 * @property Place[] $places
 * @property UserAddress[] $userAddresses
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
            [['parentid'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['parentid' => 'id']],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Place::className(), ['id' => 'parentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['parentid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::className(), ['city_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(Place::className(), ['id' => 'parentid']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Place::className(), ['id' => 'parentid']);
    }
}
