<?php

namespace api\models;

use Yii;
use api\models\UserAddress;

/**
 * This is the model class for table "sp_good_mb".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $place_id
 * @property integer $freight_id
 * @property integer $good_id
 * @property integer $cate_id
 * @property integer $brand_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 *
 * @property User $user
 * @property Place $place
 * @property Freight $freight
 * @property Good $good
 * @property Category $cate
 * @property Brand $brand
 * @property GoodMbv[] $goodMbvs
 */
class GoodMb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_mb}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'place_id', /*'freight_id',*/'address_id', 'good_id', 'cate_id', 'brand_id', 'status', 'mb_status','created_at', 'updated_at'], 'integer'],
            [['good_id', 'cate_id', 'brand_id'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            //[['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
            //[['freight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Freight::className(), 'targetAttribute' => ['freight_id' => 'id']],
            //[['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::className(), 'targetAttribute' => ['good_id' => 'id']],
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
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
            'place_id' => 'Place ID',
            'freight_id' => 'Freight ID',
            'address_id' => 'Address ID',
            'good_id' => 'Good ID',
            'cate_id' => 'Cate ID',
            'brand_id' => 'Brand ID',
            'status' => 'Status',
            'mb_status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'IS DEL',
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
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//     public function getFreight()
//     {
//         return $this->hasOne(Freight::className(), ['id' => 'freight_id']);
//     }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(Good::className(), ['id' => 'good_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
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
    public function getGoodMbv()
    {
        return $this->hasMany(GoodMbv::className(), ['mb_id' => 'id']);
    }
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrder()
    {
        return $this->hasMany(Order::className(), ['mb_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(UserAddress::className(), ['id' => 'address_id']);
    }
}
