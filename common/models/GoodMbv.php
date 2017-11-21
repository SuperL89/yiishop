<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%good_mbv}}".
 *
 * @property integer $id
 * @property integer $mb_id
 * @property string $model_text
 * @property string $price
 * @property integer $stock_num
 * @property integer $bar_code
 * @property integer $bar_code_status
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property GoodMb $mb
 * @property Order[] $orders
 */
class GoodMbv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_mbv}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mb_id', 'stock_num', 'bar_code', 'bar_code_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['model_text', 'stock_num', 'bar_code', 'bar_code_status'], 'required'],
            [['price'], 'number'],
            [['model_text'], 'string', 'max' => 255],
            [['mb_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodMb::className(), 'targetAttribute' => ['mb_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mb_id' => 'Mb ID',
            'model_text' => '型号',
            'price' => '单价',
            'stock_num' => '库存',
            'bar_code' => '条形码',
            'bar_code_status' => 'Bar Code Status',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
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
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['mbv_id' => 'id']);
    }
    const STATUS_ACTIVE = 0;
    const STATUS_DELETED = 2;
    /**
     * 设置商品属性状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已删除'];
    }
    /**
     * 获得商品属性状态并转为中文显示
     */
    public function getStatusStr()
    {
        if($this->status==self::STATUS_ACTIVE){
            return '正常';
        }elseif ($this->status==self::STATUS_DELETED){
            return '已删除';
        }else{
        return '未知';
        }
    }
}
