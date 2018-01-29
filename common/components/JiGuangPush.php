<?php
namespace common\components;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use JPush\Client as JPush;

/**
 * 文件上传处理
 */
class JiGuangPush extends Model
{   
    private $app_key = '923a29f3481ff8d308b96ff2';
    private $master_secret = '8e82a05796a90f663ef90879';
    private $platform;
    //private $tag;
    private $alias;
    //private $notification_alert;
    private $message;
    private $options;
    
    public function _init($platform, /*$tag*/ $alias/*, $notification_alert*/, $message, $options) 
    {
        parent::init();
        $this->platform = $platform;
        //$this->tag = $tag;
        $this->alias = $alias;
        //$this->notification_alert = $notification_alert;
        $this->message = $message;
        $this->options = $options;
    }
    
    public function push()
    {
        $client = new JPush($this->app_key, $this->master_secret);
        try {
            $response = $client->push()
                        ->setPlatform($this->platform)
                        //->addTag($this->tag)
                        ->addAlias($this->alias)
                        //->addAllAudience()
                        //->setNotificationAlert($this->notification_alert)
                        ->message($this->message)
                        ->options($this->options)
                        ->send();
            
            return array(
                'code' => 0
            );
        } catch (\JPush\Exceptions\APIConnectionException $e) {
            return array(
                'code' => 1,
                'msg' => $e
            );
        } catch (\JPush\Exceptions\APIRequestException $e) {
            return array(
                'code' => 1,
                'msg' => $e
            );
        }
    }
}