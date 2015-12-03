<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/3
 * Time: 0:26
 */

namespace wsl\rbac\actions;


use Yii;
use yii\base\Action;
use wsl\rbac\models\DpAdminMenu;

class IndexAction extends Action
{
    public function run()
    {
        /* @var $controller \wsl\rbac\base\Controller */
        /* @var $identity \wsl\rbac\models\DpAdminUser */
        $controller = $this->controller;
        $view = Yii::$app->getView();
        $identity = Yii::$app->user->identity;

        $view->title = $controller->getConfig('system.name');
        $user = [];
        $urls = [];
        if (!Yii::$app->user->isGuest) {
            $groupNames = $identity->getGroupNames();
            $user = [
                'username' => $identity->username,
                'nickname' => $identity->nickname,
                'isSuper' => $identity->is_super,
                'groupName' => join(',', $groupNames),
            ];
            $urls = DpAdminMenu::getUrlsByParentId($identity->is_super, $controller->menuIdList, 0);
        }

        $params = [
            'isGuest' => Yii::$app->user->isGuest,
            'loading_text' => $controller->getConfig('system.loading_text'),
            'config' => $controller->config,
            'user' => $user,
            'urls' => $urls,
        ];

        $viewPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;

        return $view->renderFile($viewPath . 'index.php', $params, $this->controller);
    }


}