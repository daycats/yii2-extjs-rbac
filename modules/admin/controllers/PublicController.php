<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/30
 * Time: 22:03
 */

namespace wsl\rbac\modules\admin\controllers;


use yii\web\Response;
use wsl\rbac\base\Controller;
use wsl\rbac\models\LoginForm;

/**
 * 公开控制器
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class PublicController extends Controller
{
    public $format = Response::FORMAT_JSON;

    public function beforeAction($action)
    {
        if ('login' == $action->id) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * 登录
     *
     * @return array
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load([
                'LoginForm' => \Yii::$app->request->post()
            ]) && $model->login()
        ) {
            /* @var $identity \wsl\rbac\models\DpAdminUser */
            $identity = \Yii::$app->user->identity;
            $groupNames = $identity->getGroupNames();
            $user = [
                'username' => $identity->username,
                'nickname' => $identity->nickname,
                'isSuper' => $identity->is_super,
                'groupName' => join(',', $groupNames),
            ];
            return $this->renderSuccess('登录成功', [
                'user' => $user
            ]);
        } else {
            foreach ($model->getErrors() as $error) {
                foreach ($error as $msg) {
                    return $this->renderError($msg);
                }
            }
        }

        return $this->renderError('未知错误');
    }

    /**
     * 注销登录
     *
     * @return array
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->renderSuccess('注销成功');
    }
}