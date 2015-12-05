<?php
namespace wsl\rbac\modules\admin\controllers;

use Yii;
use wsl\rbac\base\Controller;
use wsl\rbac\models\DpAdminMenu;

/**
 * Site controller
 */
class SiteController extends Controller
{
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
            $urls = DpAdminMenu::getUrlsByParentId($this->identity->is_super, $this->menuIdList, 0);
        }

        return $this->renderPartial('index', [
            'isGuest' => Yii::$app->user->isGuest,
            'loading_text' => $this->getConfig('system.loadingText'),
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
