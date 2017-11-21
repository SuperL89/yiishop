<?php
namespace common\components;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use crazyfd\qiniu\Qiniu;

/**
 * 文件上传处理
 */
class Upload extends Model
{   
    public $file;
    private $_appendRules;
    public function init () 
    {
        parent::init();
        $extensions = Yii::$app->params['webuploader']['baseConfig']['accept']['extensions'];
        $this->_appendRules = [
            [['file'], 'file', 'extensions' => $extensions],
        ];
    }

    public function rules()
    {
        $baseRules = [];
        return array_merge($baseRules, $this->_appendRules);
    }

    /**
     * 上传图片到本地
     */
    public function upImage()
    {
        $model = new static;
        $model->file = UploadedFile::getInstanceByName('file');
        if (!$model->file) {
            return false;
        }
        $relativePath = $successPath = '';
        if ($model->validate()) {
            $relativePath = Yii::$app->params['imageUploadRelativePath'];
            $successPath = Yii::$app->params['imageUploadSuccessPath'];
            $fileName = $model->file->baseName . '.' . $model->file->extension;
            if (!is_dir($relativePath)) {
                FileHelper::createDirectory($relativePath);    
            }
            $model->file->saveAs($relativePath . $fileName);
            return [
                'code' => 0,
                'url' => Yii::$app->params['domain'] . $successPath . $fileName,
                'attachment' => $successPath . $fileName
            ];
        } else {
            $errors = $model->errors;
            return [
                'code' => 1,
                'msg' => current($errors)[0]
            ];
        }
    }
    
    /**
     * 上传图片到七牛云
     */
    public function upImageToQny()
    {
        $model = new static;
        $model->file = UploadedFile::getInstanceByName('file');
        if (!$model->file) {
            return false;
        }
        
        if ($model->validate()) {
            //上传图片到七牛云
            $qiniu = Yii::$app->qiniu;
            $key =md5($model->file->baseName.'.'.time());
            $qiniu->uploadFile($model->file->tempName, $key);
            $qiniu->uploadFile($model->file->tempName);
            $url = $qiniu->getLink($key);
            
            return [
                'code' => 0,
                'url' => $url,
                'attachment' => $url
            ];
        } else {
            $errors = $model->errors;
            return [
                'code' => 1,
                'msg' => current($errors)[0]
            ];
        }
    }
}