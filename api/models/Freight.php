<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_freight".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $freight_text
 *
 * @property User $user
 * @property GoodMb[] $goodMbs
 */
class Freight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%freight}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'freight_text'], 'required'],
            [['user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['freight_text'], 'string', 'max' => 5000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'freight_text' => 'Freight Text',
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
    public function getGoodMbs()
    {
        return $this->hasMany(GoodMb::className(), ['freight_id' => 'id']);
    }
}
