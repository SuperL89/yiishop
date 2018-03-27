<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_good_mbv".
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
            [['model_text', 'price', 'stock_num', 'bar_code'], 'required'],
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
            'model_text' => 'Model Text',
            'price' => 'Price',
            'stock_num' => 'Stock Num',
            'bar_code' => 'Bar Code',
            'bar_code_status' => 'Bar Code Status',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodMb()
    {
        return $this->hasOne(GoodMb::className(), ['id' => 'mb_id']);
    }
}
