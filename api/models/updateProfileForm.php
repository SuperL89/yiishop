<?php
namespace api\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class updateProfileForm extends Model
{
    
    public $nickname;
    public $sex;
    public $image_h;
    
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            //用户信息
            ['nickname', 'trim'],
            ['nickname', 'required', 'message' => '昵称不能为空.'],
           
            ['sex', 'trim'],
            ['sex', 'required', 'message' => '性别不能为空.'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'nickname' => '昵称',
            'sex' => '性别',
            'image_h' => '头像',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function update($id)
    {
        $user = User::findOne($id);
        $user->nickname = $this->nickname;
        $user->sex = $this->sex;
        $user->image_h = $this->image_h;
        $user->updated_at = time();
        return $user->save() ? $user : null;
    }
}
