<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "sp_sms_log".
 *
 * @property integer $id
 * @property string $to
 * @property string $usage
 * @property string $code
 * @property integer $used
 * @property integer $use_time
 * @property integer $create_time
 */
class SmsLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sp_sms_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['used', 'use_time', 'create_time'], 'integer'],
            [['to', 'usage'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'to' => '接收手机号',
            'usage' => '用途',
            'code' => '验证码',
            'used' => '是否使用',
            'use_time' => '使用时间',
            'create_time' => '发送时间',
        ];
    }
    /**
    +----------------------------------------------------------
     * 产生随机字串，可用来自动生成密码
     * 默认长度6位 字母和数字混合 支持中文
    +----------------------------------------------------------
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @param string $addChars 额外字符
    +----------------------------------------------------------
     * @return string
    +----------------------------------------------------------
     */
    public function rand_string($len = 6, $type = '', $addChars = '') {
        $str = '';
        switch ($type) {
            case 0 :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $addChars;
                break;
            case 1 :
                $chars = str_repeat ( '0123456789', 3 );
                break;
            case 2 :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3 :
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 5 :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' . $addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789' . $addChars;
                break;
        }
        if ($len > 10) { //位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
        }
        if ($type != 4) {
            $chars = str_shuffle ( $chars );
            $str = substr ( $chars, 0, $len );
        } else {
            // 中文随机字
            for($i = 0; $i < $len; $i ++) {
                $str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
            }
        }
        return $str;
    }

}
