<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $city_id
 * @property string $street
 * @property string $phone
 * @property integer $status
 *
 * @property User $user
 * @property Place $city
 */
class UserAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['user_id', 'name', 'city_id', 'street', 'phone', 'status'], 'required'],
//             [['user_id', 'city_id', 'status'], 'integer'],
//             [['name'], 'string', 'max' => 100],
//             [['street'], 'string', 'max' => 500],
//             [['phone'], 'string', 'max' => 50],
//             [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
//             [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'city_id' => 'City ID',
            'street' => 'Street',
            'phone' => 'Phone',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Place::className(), ['id' => 'city_id']);
    }
}
