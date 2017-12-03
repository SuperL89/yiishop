<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%good_code}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property string $model_text
 * @property string $bar_code
 * @property integer $created_at
 * @property integer $updated_at
 */
class GoodCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'model_text', 'bar_code'], 'required'],
            [['good_id', 'created_at', 'updated_at'], 'integer'],
            [['model_text'], 'string', 'max' => 50],
            [['bar_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Good ID',
            'model_text' => 'Model Text',
            'bar_code' => 'Bar Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
