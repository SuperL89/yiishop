<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Banner;


/**
 * 上传文件必须配置两个参数
 *
 * 1. 在 `/common/config/bootstrap.php` 文件中,配置`@uploadPath`的值,例如:`dirname(dirname(__DIR__)) . '/frontend/web/uploads'`
 *
 * 2. 在 `/backend/config/params.php` 文件中,配置`assetDomain`的值,例如:`http://localhost/yii2/advanced/frontend/web/uploads`
 *
 * Class UploadForm
 * @package backend\models
 */
class UploadForm extends Model
{
    public $image_url;
    public function rules()
    {
        return [
            //数据验证这里可自己做
            [
                ['image_url'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png , jpg'],
            ];
    }

    /*public function upload()
    {
        if ($this->validate()) {
            $path = Yii::getAlias('@uploadPath') . '/' . date("Ymd");
            if (!is_dir($path) || !is_writable($path)) {
                \yii\helpers\FileHelper::createDirectory($path, 0777, true);
            }
            print_r($this);exit();
            $filePath = $path . '/' . md5(uniqid() . mt_rand(10000, 99999999)) . '.jpg';
           
            if ($this->image_url->saveAs($filePath)) {

                //这里将上传成功后的图片信息保存到数据库
                $imageUrl = $this->parseImageUrl($filePath);
                $imageModel = new Banner();
                $imageModel->image_url = $imageUrl;
                $imageModel->status = 0;
                $imageModel->save(false);
                $imageId = Yii::$app->db->getLastInsertID();

                return ['imageUrl' => $imageUrl, 'imageId' => $imageId];
            }
        }
        return false;
    }*/
    
    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::getAlias('@uploadPath') . '/' . date("Ymd");
            if (!is_dir($path) || !is_writable($path)) {
                \yii\helpers\FileHelper::createDirectory($path, 0777, true);
            }
            $filePath = $path . '/' . md5(uniqid() . mt_rand(10000, 99999999)) . '.' . $this->image_url->extension;
            //print_r($this->image_url->saveAs($filePath));exit();
            if ($this->image_url->saveAs($filePath)) {
                $imageUrl = $this->parseImageUrl($filePath);
                $imageId = Yii::$app->db->getLastInsertID();
                return ['imageUrl' => $imageUrl, 'imageId' => $imageId];
            }
            //return false;
        }
        return false;
    }

    /**
     * 这里在upload中定义了上传目录根目录别名，以及图片域名
     * 将/var/www/html/advanced/frontend/web/uploads/20160626/file.png 转化为 http://statics.gushanxia.com/uploads/20160626/file.png
     * format:http://domain/path/file.extension
     * @param $filePath
     * @return string
     */
    private function parseImageUrl($filePath)
    {
        if (strpos($filePath, Yii::getAlias('@uploadPath')) !== false) {
            return Yii::$app->params['assetDomain'] . str_replace(Yii::getAlias('@uploadPath'), '', $filePath);
        } else {
            return $filePath;
        }
    }
}