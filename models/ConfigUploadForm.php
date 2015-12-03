<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/9/5
 * Time: 1:24
 */

namespace wsl\rbac\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * 配置上传表单
 *
 * @package backend\models
 */
class ConfigUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    /**
     * @var string 保存路径
     */
    public $savePath = '';

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'uploadRequired' => '请上传LOGO'],
        ];
    }

    public function upload()
    {
        if ($this->validate() && $this->imageFile) {
            $uploadDir = ArrayHelper::getValue(\Yii::$app->params, 'upload.common') . date('Ymd') . '/';
            if (!FileHelper::createDirectory($uploadDir)) {
                $this->addError('文件夹创建失败！请检查权限！', 'imageFile');
                return false;
            }
            $this->savePath = $uploadDir . \Yii::$app->getSecurity()->generateRandomString() . '.' . $this->imageFile->extension;
            return $this->imageFile->saveAs($this->savePath);
        } else {
            return false;
        }
    }

}