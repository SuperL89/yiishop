<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_good_clicks".
 *
 * @property integer $id
 * @property integer $clicks
 * @property integer $good_id
 *
 * @property Good $good
 */
class GoodClicks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_clicks}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clicks', 'good_id'], 'integer'],
            [['good_id'], 'required'],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Good::className(), 'targetAttribute' => ['good_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clicks' => 'Clicks',
            'good_id' => 'Good ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood()
    {
        return $this->hasOne(Good::className(), ['id' => 'good_id']);
    }
}
