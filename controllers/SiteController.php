<?php
namespace wsl\rbac\controllers;

use wsl\rbac\base\Controller;
use wsl\rbac\models\DpAdminMenu;
use Yii;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function getViewPath()
    {
        return Yii::getAlias('@wsl/rbac/controllers/views') . DIRECTORY_SEPARATOR . $this->id;
    }

    public function actionIndex()
    {
        $this->view->title = $this->getConfig('system.name');
        $user = [];
        $urls = [];
        if (!Yii::$app->user->isGuest) {
            $groupNames = $this->identity->getGroupNames();
            $user = [
                'username' => $this->identity->username,
                'nickname' => $this->identity->nickname,
                'isSuper' => $this->identity->is_super,
                'groupName' => join(',', $groupNames),
            ];
            $urls = array_merge($urls, [
                'admin.public.logout' => Url::toRoute('/admin/public/logout'),
                'admin.common.tree' => Url::toRoute('/admin/common/tree'),
                'admin.common.urls' => Url::toRoute('/admin/common/urls'),
                'admin.public.login' => Url::toRoute('/admin/public/login'),
                'admin.config.save' => Url::toRoute('/admin/config/save'),
                'admin.config.options' => Url::toRoute('/admin/config/options'),
            ], DpAdminMenu::getUrlsByParentId($this->identity->is_super, $this->menuIdList, 0));
        }

        return $this->renderPartial('index', [
            'isGuest' => Yii::$app->user->isGuest,
            'loading_text' => $this->getConfig('system.loading_text'),
            'config' => $this->config,
            'user' => $user,
            'urls' => $urls,
        ]);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        return $this->renderPartial('error', [
            'message' => $exception->getMessage(),
        ]);
    }
}
