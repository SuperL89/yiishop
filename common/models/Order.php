<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sp_order".
 *
 * @property integer $id
 * @property string $order_num
 * @property integer $user_id
 * @property integer $business_id
 * @property integer $good_id
 * @property integer $mb_id
 * @property integer $mbv_id
 * @property string $user_address
 * @property integer $pay_type
 * @property string $good_price
 * @property integer $pay_num
 * @property string $good_total_price
 * @property string $order_fare
 * @property string $order_total_price
 * @property string $express_name
 * @property string $express_num
 * @property integer $status
 * @property integer $created_at
 * @property integer $pay_at
 * @property integer $deliver_at
 * @property integer $complete_at
 * @property string $good_var
 * @property string $cancel_text
 * @property string $message
 *
 * @property User $user
 * @property User $business
 * @property Good $good
 * @property GoodMb $mb
 * @property GoodMbv $mbv
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['order_num', 'user_id', 'business_id', 'good_id', 'mb_id', 'mbv_id', 'user_address', 'good_price', 'pay_num', 'good_total_price', 'order_fare', 'order_total_price', 'created_at'], 'required'],
//             [['user_id', 'business_id', 'good_id', 'mb_id', 'mbv_id', 'pay_type', 'pay_num', 'status', 'created_at', 'pay_at', 'deliver_at', 'complete_at'], 'integer'],
//             [['good_price', 'good_total_price', 'order_fare', 'order_total_price'], 'number'],
//             [['order_num'], 'string', 'max' => 128],
//             [['user_address', 'good_var'], 'string', 'max' => 1000],
//             [['express_name', 'express_num'], 'string', 'max' => 50],
//             [['cancel_text'], 'string', 'max' => 100],
//             [['message'], 'string', 'max' => 300],
//             [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
//             [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['business_id' => 'id']],
//             [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::className(), 'targetAttribute' => ['good_id' => 'id']],
//             [['mb_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMb::className(), 'targetAttribute' => ['mb_id' => 'id']],
//             [['mbv_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMbv::className(), 'targetAttribute' => ['mbv_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_num' => '订单编号',
            'user_id' => '用户id',
            'business_id' => '商家用户id',
            'good_id' => '商品id',
            'mb_id' => '商家报价id',
            'mbv_id' => '商品属性id',
            'user_address' => '用户收货地址',
            'pay_type' => '支付方式',
            'good_price' => '商品单价',
            'pay_num' => '购买数量',
            'good_total_price' => '商品总价',
            'order_fare' => '运费',
            'order_total_price' => '订单总价',
            'express_name' => '快递名称',
            'express_num' => '快递单号',
            'status' => '订单状态',
            'created_at' => '创建时间',
            'pay_at' => '支付时间',
            'deliver_at' => '发货时间',
            'complete_at' => '完成时间',
            'good_var' => '商品信息',
            'cancel_text' => '订单取消原因',
            'message' => '用户留言',
            'library_at' => '出库时间',
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
    public function getBusiness()
    {
        return $this->hasOne(User::className(), ['id' => 'business_id']);
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
    public function getMb()
    {
        return $this->hasOne(GoodMb::className(), ['id' => 'mb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMbv()
    {
        return $this->hasOne(GoodMbv::className(), ['id' => 'mbv_id']);
    }
    
    const PAY_TYPE = 0;//余额支付
    const PAY_TYPE_ALIPAY = 1;//支付宝
    /**
     * 设置用户支付方式常量
     */
    public static function allPaytype()
    {
        return [self::PAY_TYPE=>'余额',self::PAY_TYPE_ALIPAY=>'支付宝'];
    }
    /**
     * 获得用户支付方式并转为中文显示
     */
    public function getPaytypeStr()
    {
        if($this->pay_type==self::PAY_TYPE){
            return '余额';
        }elseif ($this->pay_type==self::PAY_TYPE_ALIPAY){
            return '支付宝';
        }else{
            return '暂无';
        }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
    
    const STATUS_1 = 1;//待支付
    const STATUS_2 = 2;//待发货
    const STATUS_3 = 3;//已发货
    const STATUS_4 = 4;//已完成
    const STATUS_5 = 5;//已出库
    /**
     * 设置订单状态常量
     */
    public static function allStatus()
    {
        return [self::STATUS_1=>'待支付',self::STATUS_2=>'待发货',self::STATUS_3=>'已发货',self::STATUS_4=>'已完成',self::STATUS_5=>'已出库'];
    }
    /**
     * 获得订单状态并转为中文显示
     */
    public function getStatusStr()
    {
//         if($this->pay_type==self::PAY_TYPE){
//             return '余额';
//         }elseif ($this->pay_type==self::PAY_TYPE_ALIPAY){
//             return '支付宝';
//         }else{
//             return '暂无';
//         }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
        $status = '';
        switch ($this->status){
            case 1:
              $status = '待支付';
              break;
            case 2:
              $status = '待发货';
              break;
            case 3:
              $status = '已发货';
              break;
            case 4:
              $status = '已完成';
              break;
            case 5:
              $status = '已出库';
              break;
       }
       return $status;
    }
    
    public function getGoodvar()
    {
        return json_decode($this->good_var);
    }
    public function getUseraddress()
    {
        $address = json_decode($this->user_address);
        
        $address = "$address->name "."$address->phone "."$address->address_en"."($address->address_cn) "."$address->address_street";
        //print_r($address);exit();
        return $address;
        //return json_decode($this->user_address);
    }
}
