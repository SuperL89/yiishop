<?php

namespace common\models;

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
 * @property string $cate_id
 * @property integer $status
 * @property integer $score
 * @property integer $score_updated_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
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
            [['user_id', 'image_url', 'city_id', 'address', 'cate_id', 'score_updated_at', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'city_id', 'status', 'score', 'score_updated_at', 'created_at', 'updated_at'], 'integer'],
            [['image_url'], 'string', 'max' => 1000],
            [['name', 'cate_id'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'name' => '真实姓名',
            'city_id' => 'City ID',
            'address' => '详细地址',
            'cate_id' => 'Cate ID',
            'status' => '状态',
            'score' => '评分',
            'score_updated_at' => '最近评分改变时间',
            'created_at' => '申请时间',
            'updated_at' => '更新时间',
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
        return $this->hasOne(Place::className(), ['id' => 'city_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCates()
    {
        $cate_id_arr = explode(',', $this->cate_id);
        $cate_names=Category::find()->select(['id','title'])->where(['in', 'id', $cate_id_arr])->all();
        foreach ($cate_names as $k =>$v){
            $cate_name[$k] = $v->title;
        }
        $cate_name = implode(',', $cate_name);
        return $cate_name;
    }
    
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_ACTIVEING = 0;
    /**
     * 设置商家状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'审核通过',self::STATUS_ACTIVEING=>'待审核',self::STATUS_DELETED=>'审核拒绝'];
    }
    /**
     * 获得商家状态并转为中文显示
     */
    public function getStatusStr()
    {
        if($this->status==self::STATUS_ACTIVE){
            return '审核通过';
        }elseif ($this->status==self::STATUS_ACTIVEING){
            return '待审核';
        }elseif($this->status==self::STATUS_DELETED){
            return '审核拒绝';
        }else{
            return '未知';
        }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
}
