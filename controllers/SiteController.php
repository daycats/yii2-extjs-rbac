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
                'admin.group.save' => Url::toRoute('/admin/group/save'),
                'admin.group.update-status' => Url::toRoute('/admin/group/update-status'),
                'admin.group.del' => Url::toRoute('/admin/group/del'),
                'admin.user.save' => Url::toRoute('/admin/user/save'),
                'admin.user.update-status' => Url::toRoute('/admin/user/update-status'),
                'admin.user.del' => Url::toRoute('/admin/user/del'),
                'admin.common.urls' => Url::toRoute('/admin/common/urls'),
                'admin.public.login' => Url::toRoute('/admin/public/login'),
                'admin.menu.save' => Url::toRoute('/admin/menu/save'),
                'admin.menu.update-status' => Url::toRoute('/admin/menu/update-status'),
                'admin.menu.del' => Url::toRoute('/admin/menu/del'),
                'admin.menu-url.save' => Url::toRoute('/admin/menu-url/save'),
                'admin.menu-url.update-status' => Url::toRoute('/admin/menu-url/update-status'),
                'admin.menu-url.del' => Url::toRoute('/admin/menu-url/del'),
                'admin.menu-url-link.save' => Url::toRoute('/admin/menu-url-link/save'),
                'admin.menu-url-link.update-status' => Url::toRoute('/admin/menu-url-link/update-status'),
                'admin.menu-url-link.del' => Url::toRoute('/admin/menu-url-link/del'),
                'admin.menu-url-rule.save' => Url::toRoute('/admin/menu-url-rule/save'),
                'admin.menu-url-rule.update-status' => Url::toRoute('/admin/menu-url-rule/update-status'),
                'admin.menu-url-rule.del' => Url::toRoute('/admin/menu-url-rule/del'),
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
