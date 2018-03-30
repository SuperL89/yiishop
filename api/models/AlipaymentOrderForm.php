<?php

namespace api\models;

use Yii;

class AlipaymentOrderForm extends \yii\db\ActiveRecord
{
    public $username;
    public $order_id;

    private $_user;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message' => '手机号/用户名不能为空.'],
            ['username', 'exist', 'targetClass' => '\api\models\User', 'message' => '用户不存在.'],
            //国内手机号验证规则
//          ['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'手机号/用户名不符合规则'],
            //美国手机验证规则
            //['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'非美国手机用户不能通过'],
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
        ];
    }
    
   
    public function attributeLabels()
    {
        return [
            'username' =>'用户名/手机号',
            'order_id' => '订单编号',
        ];
    }
    
    /**
     * 根据用户名获取用户的认证信息
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
    
        return $this->_user;
    }
}
