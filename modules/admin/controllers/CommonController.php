<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/30
 * Time: 22:04
 */

namespace wsl\rbac\modules\admin\controllers;


use wsl\rbac\base\Controller;
use wsl\rbac\models\DpAdminMenu;
use yii\helpers\Url;
use yii\web\Response;

/**
 * 公共控制器
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class CommonController extends Controller
{
    public $format = Response::FORMAT_JSON;

    /**
     * 树形菜单数据
     *
     * @return array
     */
    public function actionTree()
    {
        $tree = DpAdminMenu::getAccessTreeByParentId($this->identity->is_super, $this->menuIdList, null, [
            'is_hide' => 0,
        ]);

        return [
            'children' => $tree,
        ];
    }

    /**
     * 所有拥有权限的url别名对应url数据
     *
     * @return array
     */
    public function actionUrls()
    {
        $urls = DpAdminMenu::getUrlsByParentId($this->identity->is_super, $this->menuIdList, null);

        $urls = array_merge($urls, [
            'admin.public.login' => Url::toRoute('/admin/public/login'),
            'admin.public.logout' => Url::toRoute('/admin/public/logout'),
            'admin.common.tree' => Url::toRoute('/admin/common/tree'),
            'admin.common.urls' => Url::toRoute('/admin/common/urls'),

            'admin.config.save' => Url::toRoute('/admin/config/save'),
            'admin.config.options' => Url::toRoute('/admin/config/options'),
            'admin.group.save' => Url::toRoute('/admin/group/save'),
            'admin.group.update-status' => Url::toRoute('/admin/group/update-status'),
            'admin.group.del' => Url::toRoute('/admin/group/del'),
            'admin.user.save' => Url::toRoute('/admin/user/save'),
            'admin.user.update-status' => Url::toRoute('/admin/user/update-status'),
            'admin.user.del' => Url::toRoute('/admin/user/del'),
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
        ], DpAdminMenu::getUrlsByParentId($this->identity->is_super, $this->menuIdList, null));

        return [
            'data' => $urls,
        ];
    }
}