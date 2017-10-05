<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sp_banner".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_url
 * @property string $ad_url 
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $order
 */
class Banner extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 0;
    
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'image_url'], 'required'],
            [['image_url'], 'safe'],
            [['created_at', 'updated_at', 'status','order'], 'integer'],
            [['title','ad_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'image_url' => '图片Url',
            'ad_url' => 'Url',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态',
            'order' => '排序',
        ];
    }
    
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已禁用'];
    }
    /**
     * 获得用户状态并转为中文显示
     */
    public function getStatusStr()
    {
        return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
}
