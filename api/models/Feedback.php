<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property string $text_a
 * @property integer $created_at
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text_a', 'created_at'], 'required'],
            [['text_a'], 'string'],
            [['created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text_a' => 'Text A',
            'created_at' => 'Created At',
        ];
    }
}
