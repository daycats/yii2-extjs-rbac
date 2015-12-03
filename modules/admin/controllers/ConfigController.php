<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/9/2
 * Time: 14:08
 */

namespace wsl\rbac\modules\admin\controllers;


use yii\web\Response;
use yii\web\UploadedFile;
use wsl\rbac\base\Controller;
use wsl\rbac\models\ConfigUploadForm;
use wsl\rbac\models\DpConfig;

class ConfigController extends Controller
{
    public $format = Response::FORMAT_JSON;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionOptions()
    {
        $data = DpConfig::find()->all();
        $config = [];
        foreach ($data as $item) {
            $config['config[' . $item['name'] . ']'] = $item['value'];
        }

        return [
            'data' => $config,
        ];
    }

    /**
     * 保存数据
     *
     * @return array
     */
    public function actionSave()
    {
        $config = \Yii::$app->request->post('config');
        if ($config) {
            foreach ($config as $name => $value) {
                DpConfig::replaceByName($name, $value, 1);
            }
            // logo上传
            $uploadModel = new ConfigUploadForm();
            $uploadModel->imageFile = UploadedFile::getInstance($uploadModel, 'logo_file');
            if ($uploadModel->upload()) {
                DpConfig::replaceByName('website.logo', $uploadModel->savePath, 1);
            } elseif (!$this->getConfig('website.logo')) {
                foreach ($uploadModel->getErrors() as $error) {
                    foreach ($error as $message) {
                        return $this->renderError($message);
                    }
                }
            }
            // 二维码上传
            $uploadModel = new ConfigUploadForm();
            $uploadModel->imageFile = UploadedFile::getInstance($uploadModel, 'qr_file');
            if ($uploadModel->upload()) {
                DpConfig::replaceByName('website.qr', $uploadModel->savePath, 1);
            } elseif (!$this->getConfig('website.qr')) {
                foreach ($uploadModel->getErrors() as $error) {
                    foreach ($error as $message) {
                        return $this->renderError($message);
                    }
                }
            }
            return $this->renderSuccess('保存成功');
        } else {
            return $this->renderError('数据不能为空');
        }
    }
}