<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%express}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Express extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '快递公司名称',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 0;
    /**
     * 设置商家状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已禁止'];
    }
    /**
     * 获得商家状态并转为中文显示
     */
    public function getStatusStr()
    {
        if($this->status==self::STATUS_ACTIVE){
            return '正常';
        }elseif($this->status==self::STATUS_DELETED){
            return '已禁止';
        }else{
            return '未知';
        }
        //return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
}
