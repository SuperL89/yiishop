<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%business}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $image_url
 * @property string $name
 * @property integer $city_id
 * @property string $address
 * @property integer $cate_id
 * @property integer $stastus
 * @property integer $score
 * @property integer $score_updated_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Place $city
 * @property Category $cate
 */
class Business extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_url'], 'required', 'message' => '认证图片不能为空.'],
            [['name'], 'required', 'message' => '商家姓名不能为空.'],
            [['city_id'], 'required', 'message' => '发货地不能为空.'],
            [['address'], 'required', 'message' => '详细地址不能为空.'],
            [['cate_id'], 'required', 'message' => '商家分类不能为空.'],
            
            [['user_id', 'city_id'], 'integer','message' => '参数类型有误'],
            [['image_url'], 'string', 'max' => 1000, 'message' => '认证图片超出范围.'],
            [['name'], 'string', 'max' => 50, 'message' => '商家姓名超出范围.'],
            [['address'], 'string', 'max' => 500, 'message' => '详细地址超出范围.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['city_id' => 'id'], 'message' => '无此城市.'],
            //[['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id'], 'message' => '无此分类.'],
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
            'image_url' => 'Image Url',
            'name' => 'Name',
            'city_id' => 'City ID',
            'address' => 'Address',
            'cate_id' => 'Cate ID',
            'stastus' => 'Stastus',
            'score' => 'Score',
            'score_updated_at' => 'Score Updated At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
    }
}
