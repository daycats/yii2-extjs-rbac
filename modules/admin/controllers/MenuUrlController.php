<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/31
 * Time: 21:52
 */

namespace wsl\rbac\modules\admin\controllers;


use wsl\rbac\base\Controller;
use wsl\rbac\helpers\ExtHelpers;
use wsl\rbac\models\DpAdminMenu;
use wsl\rbac\models\DpAdminMenuUrl;
use wsl\rbac\models\DpAdminMenuUrlRelation;
use wsl\rbac\models\search\DpAdminMenuUrlSearch;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 菜单URL
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class MenuUrlController extends Controller
{
    public $format = Response::FORMAT_JSON;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 所有列表
     *
     * @param string $sort 排序
     * @return array
     */
    public function actionAll($sort = '')
    {
        $order = ExtHelpers::getOrder($sort, [
            'url_id',
            'route',
            'method',
            'host',
            'enable_rule',
            'note',
            'status'
        ], [], 'url_id DESC');
        $list = DpAdminMenuUrl::find()->orderBy($order)->all();

        return [
            'total' => count($list),
            'list' => $list,
        ];
    }

    /**
     * 列表
     *
     * @param string $sort 排序
     * @return array
     */
    public function actionList($sort = '')
    {
        $order = ExtHelpers::getOrder($sort, [
            'url_id',
            'route',
            'method',
            'host',
            'enable_rule',
            'note',
            'status'
        ], [], 'url_id DESC');

        $list = [];

        $searchModel = new DpAdminMenuUrlSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get(), '');
        $dataProvider->query->orderBy($order);
        $dataProvider->getPagination()->pageSizeParam = 'limit';
        foreach ($dataProvider->getModels() as $itemModel) {
            /* @var $itemModel \wsl\rbac\models\DpAdminMenuUrl */
            $list[] = $itemModel->getAttributes();
        }

        return [
            'total' => $dataProvider->getTotalCount(),
            'list' => $list,
        ];
    }

    /**
     * 保存数据
     *
     * @return array
     */
    public function actionSave()
    {
        $url_id = \Yii::$app->request->post('url_id');
        $menu_id = \Yii::$app->request->post('menu_id');
        $name = \Yii::$app->request->post('name');
        $alias = \Yii::$app->request->post('alias');
        $route = \Yii::$app->request->post('route');
        $method = \Yii::$app->request->post('method');
        $host = \Yii::$app->request->post('host');
        $enable_rule = \Yii::$app->request->post('enable_rule');
        $note = \Yii::$app->request->post('note');
        $status = \Yii::$app->request->post('status');

        if (!$alias) {
            // 自动生成别名
            if (strpos($route, '/') === 0) {
                $tempRoute = substr($route, 1, strlen($route));
            } else {
                $tempRoute = $route;
            }
            $alias = join('.', StringHelper::explode($tempRoute, '/', true, true));
        }
        $methodStr = '';
        if (is_array($method)) {
            $method = array_filter($method, function ($value) {
                return in_array($value, ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS']);
            });
            $methodStr = join(',', $method);
        }
        $saveData = [
            'name' => $name,
            'alias' => $alias,
            'route' => $route,
            'method' => $methodStr,
            'host' => $host,
            'enable_rule' => $enable_rule,
            'note' => $note,
            'status' => $status,
        ];
        $obj = null;
        if ($url_id) {
            $obj = DpAdminMenuUrl::find()
                ->findByUrlId($url_id)
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            if ($alias) {
                $obj = DpAdminMenuUrl::find()
                    ->findByAlias($alias)
                    ->one();
            }
            if (!$obj) {
                $obj = new DpAdminMenuUrl();
            }
        }
        $obj->setAttributes($saveData);
        if (!$obj->save()) {
            foreach ($obj->getErrors() as $error) {
                foreach ($error as $message) {
                    return [
                        'success' => false,
                        'msg' => $message,
                    ];
                }
            }
        }
        if ($menu_id) {
            if (!DpAdminMenu::getByMenuId($menu_id)) {
                return $this->renderError('菜单不存在');
            } else {
                $linkObj = new DpAdminMenuUrlRelation();
                $linkObj->setAttributes([
                    'menu_id' => $menu_id,
                    'url_id' => $obj->url_id,
                    'status' => DpAdminMenuUrlRelation::STATUS_ACTIVE,
                ]);
                if (!$linkObj->save()) {
                    foreach ($obj->getErrors() as $error) {
                        foreach ($error as $message) {
                            return [
                                'success' => false,
                                'msg' => $message,
                            ];
                        }
                    }
                }
            }
        }

        return $this->renderSuccess('保存成功');
    }

    /**
     * 删除(设置一个删除的标识)
     *
     * @return array
     */
    public function actionDel()
    {
        $ids = \Yii::$app->request->post('ids');
        foreach (StringHelper::explode($ids, ',', true, true) as $id) {
            $obj = DpAdminMenuUrl::find()
                ->findByUrlId($id)
                ->one();
            if ($obj) {
                $obj->status = DpAdminMenuUrl::STATUS_DELETE;
                $obj->save();
            }
        }

        return $this->renderSuccess('删除成功');
    }

    /**
     * 更新状态
     *
     * @return array
     */
    public function actionUpdateStatus()
    {
        $ids = \Yii::$app->request->post('ids');
        $status = intval(\Yii::$app->request->post('status'));
        if ($status != 0) {
            $status = 1;
        }
        foreach (StringHelper::explode($ids, ',', true, true) as $id) {
            $obj = DpAdminMenuUrl::find()
                ->findByUrlId($id)
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }

}