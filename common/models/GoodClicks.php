<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%good_clicks}}".
 *
 * @property integer $id
 * @property integer $clicks
 * @property integer $good_id
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
}
