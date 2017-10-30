<?php

namespace api\models;

use Yii;
use api\models\Freight;

class FreightForm extends \yii\db\ActiveRecord
{
    public $freight_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['freight_id', 'required', 'message' => '运费模版id不能为空.'],
            [['freight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Freight::className(), 'targetAttribute' => ['freight_id' => 'id'],'message' => '运费模版不存在.'],
        ];
    }
}
