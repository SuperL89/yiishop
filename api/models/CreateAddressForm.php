<?php

namespace api\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $city_id
 * @property string $street
 * @property string $phone
 * @property integer $status
 *
 * @property User $user
 * @property Place $city
 */
class CreateAddressForm extends \yii\db\ActiveRecord
{
    public $user_id;
    public $name;
    public $city_id;
    public $street;
    public $phone;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['user_id', 'required', 'message' => '用户不能为空.'],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id'],'message' => '用户不存在.'],
            ['name', 'required', 'message' => '收件人不能为空.'],
            ['city_id', 'required', 'message' => '城市不能为空.'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['city_id' => 'id'],'message' => '无此城市信息'],
            ['street', 'required', 'message' => '街道不能为空.'],
            ['phone', 'required', 'message' => '电话不能为空.'],
            //['status', 'required', 'message' => '状态不能为空.'],
            //['user_id', 'integer','message' => '用户id类型不正确.'],
            ['city_id', 'integer','message' => '城市id类型不正确.'],  
            ['status', 'integer','message' => '状态类型不正确.'],
            ['status', 'in', 'range' => [0, 1, 2], 'message' => '状态值超出范围.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'city_id' => 'City ID',
            'street' => 'Street',
            'phone' => 'Phone',
            'status' => 'Status',
        ];
    }

    /**
     * 创建收货地址
     */
    public function createaddress($user_id)
    {
    //变更默认地址
        if($this->status == 1){
            $user = UserAddress::find()->select(['id'])->where(['user_id'=>$user_id,'status' => 1])->one();
            if($user){
                $user->status = 0;
                $user->save();
            }
        }
        
        //根据城市ID查询州ID、国家ID
        $place = Place::find()->select(['*'])->where(['id' => $this->city_id, 'status' => 0])->with([
            'state' => function ($query) {
                $query->select('id,title,parentid')->andWhere('status = 0')->with([
                    'country' => function ($query) {
                        $query->select('id,title,parentid')->andWhere('status = 0');
                    }
                ]);
            }
        ])
        ->one();
        //验证州ID、国家ID
        $country_id = isset($place->state->country->id) && $place->state->country->id ? $place->state->country->id : 0;
        $state_id = isset($place->state->id) && $place->state->id ? $place->state->id : 0;
        if (empty($country_id)) return null;
        if (empty($state_id)) return null;
            
        $user = new UserAddress();
        $user->user_id =$user_id;
        $user->name = $this->name;
        $user->country_id = $country_id;
        $user->state_id = $state_id;
        $user->city_id = $this->city_id;
        $user->street = $this->street;
        $user->phone = $this->phone;
        $user->status = $this->status?:0;
        //$user->save(); VarDumper::dump($user->errors);exit();
        return $user->save() ? $user : null;
    }
}
