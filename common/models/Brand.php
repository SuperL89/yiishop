<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sp_brand".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_url
 * @property integer $cate_id
 * @property integer $status
 * @property integer $order
 * @property integer $is_hot
 *
 * @property Category $cate
 */
class Brand extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 0;
    const hot_no = 0;
    const hot_yes = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','cate_id'], 'required'],
            [['cate_id', 'status', 'order', 'is_hot'], 'integer'],
            [['title'], 'string', 'max' => 55],
            [['image_url'], 'string', 'max' => 255],
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cate_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '品牌名称',
            'image_url' => '品牌图片地址',
            'cate_id' => '一级分类',
            'status' => '状态',
            'order' => '排序',
            'is_hot' => '是否热门',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Category::className(), ['id' => 'cate_id']);
    }
    /**
     * 获取全部一级分类
     */
    public static function getAllCate()
    {
        return $this->hasMany(Category::className(), ['id'=>'cate_id']);
    }
    /**
     * 设置状态显示常量
     */
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已禁用'];
    }
    /**
     * 获得状态并转为中文显示
     */
    public function getStatusStr()
    {
        return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
    /**
     * 设置状态显示常量
     */
    public static function allHot()
    {
        return [self::hot_yes=>'是',self::hot_no=>'否'];
    }
    /**
     * 获得状态并转为中文显示
     */
    public function getHotStr()
    {
        return $this->is_hot==self::hot_yes?'是':'否';
    }
}
