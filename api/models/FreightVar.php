<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "{{%freight_var}}".
 *
 * @property integer $id
 * @property integer $freight_id
 * @property string $place_id_arr
 * @property integer $num
 * @property integer $freight
 * @property integer $numadd
 * @property integer $freightadd
 * @property integer $status
 *
 * @property Freight $freight0
 */
class FreightVar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%freight_var}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['freight_id'], 'required'],
            [['freight_id', 'num', 'freight', 'numadd', 'freightadd', 'status'], 'integer'],
            [['place_id_arr'], 'string', 'max' => 255],
            [['freight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Freight::className(), 'targetAttribute' => ['freight_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'freight_id' => 'Freight ID',
            'place_id_arr' => 'Place Id Arr',
            'num' => 'Num',
            'freight' => 'Freight',
            'numadd' => 'Numadd',
            'freightadd' => 'Freightadd',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFreight0()
    {
        return $this->hasOne(Freight::className(), ['id' => 'freight_id']);
    }
    
    public function getPlace()
    {
        $place_id_arr = explode(',', $this->place_id_arr);
        print_r($place_id_arr);
        $place_names=Place::find()->select(['*'])->where(['in', 'id', $place_id_arr])->all();
        foreach ($place_names as $k =>$v){
            $place_name[$k] = $v->name;
        }
        $place_name = implode(',', $place_name);
        return $place_name;
    }
}
