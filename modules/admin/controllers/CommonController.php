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
        $tree = DpAdminMenu::getAccessTreeByParentId($this->identity->is_super, $this->menuIdList, 0, [
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
        $urls = DpAdminMenu::getUrlsByParentId($this->identity->is_super, $this->menuIdList, 0);

        return [
            'data' => $urls,
        ];
    }
}