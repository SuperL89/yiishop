<?php

namespace api\models;

use Yii;

class PaymentOrderForm extends \yii\db\ActiveRecord
{
    public $username;
    public $order_id;
    public $pay_pw;

    private $_user;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message' => '手机号/用户名不能为空.'],
            ['username', 'exist', 'targetClass' => '\api\models\User', 'message' => '该用户不存在.'],
            //国内手机号验证规则
            ['username', 'match', 'pattern'=>'/^1[34578][0-9]{9}$/','message'=>'手机号/用户名不符合规则'],
            //美国手机验证规则
            //['username','match','pattern'=>'/^(((1(\s)|)|)[1-9]{3}(\s|-|)[1-9]{3}(\s|-|)[1-9]{4})$/','message'=>'非美国手机用户不能通过'],
            ['order_id', 'required', 'message' => '订单id不能为空.'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_num'],'message' => '订单不存在.'],
            ['pay_pw', 'required','message' => '支付密码不能为空.'],
            ['pay_pw', 'trim'],
            ['pay_pw', 'match', 'pattern'=>'/^\d{6}$/','message'=>'密码必须由6位纯数字组成'],
            ['pay_pw', 'validatePassword'],
        ];
    }
    
    /**
     * 自定义的密码认证方法
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = $this->getUser();
            if (!$this->_user || !$this->_user->validatePayPassword($this->pay_pw)) {
                $this->addError($attribute, '支付密码错误.');
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'username' =>'用户名/手机号',
            'order_id' => '订单编号',
            'pay_pw' => '密码',
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
