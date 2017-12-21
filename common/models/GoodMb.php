<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%good_mb}}".
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
 *
 * @property User $user
 * @property Good $good
 * @property Category $cate
 * @property Brand $brand
 * @property GoodMbv[] $goodMbvs
 * @property Order[] $orders
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
            [['user_id', 'place_id', /*'freight_id',*/ 'good_id', 'cate_id', 'brand_id', 'status', 'mb_status', 'created_at', 'updated_at'], 'integer'],
            [['good_id', 'cate_id', 'brand_id'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::className(), 'targetAttribute' => ['good_id' => 'id']],
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
            //[['freight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Freight::className(), 'targetAttribute' => ['freight_id' => 'id']],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['address_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商家报价id',
            'user_id' => '商家用户',
            'place_id' => '发货地',
            'freight_id' => '运费模版',
            'address_id' => 'Address ID',
            'good_id' => '商品id',
            'cate_id' => '分类id',
            'brand_id' => '品牌id',
            'status' => '状态',
            'mb_status' => '商家商品状态',
            'created_at' => '创建时间',
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
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFreight()
    {
        return $this->hasOne(Freight::className(), ['id' => 'freight_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(UserAddress::className(), ['id' => 'address_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodMbvs()
    {
        return $this->hasMany(GoodMbv::className(), ['mb_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['mb_id' => 'id']);
    }
    const STATUS_ACTIVE = 0;
    const STATUS_ACTIVEING = 1;
    
    /**
     * 设置商家报价状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'审核通过',self::STATUS_ACTIVEING=>'待审核'];
    }
    /**
     * 获得商家报价状态并转为中文显示
     */
    public function getStatusStr()
    {
        if($this->status==self::STATUS_ACTIVE){
            return '审核通过';
        }elseif ($this->status==self::STATUS_ACTIVEING){
            return '待审核';
        }else{
            return '未知';
        }
    }
    
    const STATUS_UP = 0;
    const STATUS_DW = 1;
    
    /**
     * 设置商家报价状态显示常量
     */
    public static function allStatusUpDw()
    {
        return [self::STATUS_UP=>'商家发布中',self::STATUS_DW=>'商家已下架'];
    }
    /**
     * 获得商家报价状态并转为中文显示
     */
    public function getStatusUpDwStr()
    {
        if($this->mb_status==self::STATUS_UP){
            return '商家发布中';
        }elseif ($this->mb_status==self::STATUS_DW){
            return '商家已下架';
        }else{
            return '未知';
        }
    }
    //获得地区联动
    public $place_id_1  = 0;
    
    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->setPlaces($this->place_id);
    }
    
    private function setPlaces($place_id)
    {
        $place = new Place();
        //市级信息
        $place_info = $place::findOne($place_id);
        //print_r($place_info);exit();
        if($place_info){
            //获得省级id
            $this->place_id_1 = $place_info->pid;
        }
    }
}
