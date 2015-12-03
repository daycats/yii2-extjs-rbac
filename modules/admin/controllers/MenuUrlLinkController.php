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
use wsl\rbac\models\DpAdminMenuUrl;
use wsl\rbac\models\DpAdminMenuUrlRelation;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 菜单和URL关联
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class MenuUrlLinkController extends Controller
{
    public $format = Response::FORMAT_JSON;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 列表
     *
     * @param string $sort 排序
     * @param int $page 页面
     * @param int $limit 每页记录数
     * @return array
     */
    public function actionList($sort = '', $page = 1, $limit = 20)
    {
        $link_id = \Yii::$app->request->get('link_id');
        $menu_id = \Yii::$app->request->get('menu_id');
        $url_id = \Yii::$app->request->get('url_id');
        $status = \Yii::$app->request->post('status');
        $limit = intval($limit);
        $order = ExtHelpers::getOrder($sort, [
            'link_id',
            'url_id',
        ], [], 'link_id DESC');
        $condition = '1';
        $params = [];
        $tabName = DpAdminMenuUrlRelation::tableName();
        $order = join(',', array_map(function ($value) use ($tabName) {
            return $tabName . '.' . $value;
        }, explode(',', $order)));
        if ($link_id) {
            $condition .= ' and ' . $tabName . '.link_id=:link_id';
            $params[':link_id'] = $link_id;
        }
        if ($menu_id) {
            $condition .= ' and ' . $tabName . '.menu_id=:menu_id';
            $params[':menu_id'] = $menu_id;
        }
        if ($url_id) {
            $condition .= ' and ' . $tabName . '.url_id=:url_id';
            $params[':url_id'] = $url_id;
        }
        if (is_numeric($status)) {
            $condition .= ' and ' . $tabName . '.status=:status';
            $params[':status'] = $status;
        }
        $data = DpAdminMenuUrlRelation::getListByCondition($condition, $params, $order, $page, $limit);
        if ($data['list']) {
            foreach ($data['list'] as &$item) {
                foreach ($item['menuUrl'] as $k => $v) {
                    $item['url.' . $k] = $v;
                }
                unset($item['menuUrl']);
            }
        }

        return [
            'total' => $data['pagination']['totalCount'],
            'list' => $data['list'],
        ];
    }

    /**
     * 保存数据
     *
     * @return array
     */
    public function actionSave()
    {
        $link_id = \Yii::$app->request->post('link_id');
        $menu_id = \Yii::$app->request->post('menu_id');
        $url_id = \Yii::$app->request->post('url_id');
        $status = \Yii::$app->request->post('status');

        if (!DpAdminMenuUrl::getByUrlId($url_id)) {
            return $this->renderError('菜单URL不存在！');
        }
        $saveData = [
            'menu_id' => $menu_id,
            'url_id' => $url_id,
            'status' => $status,
        ];
        if ($link_id) {
            $obj = DpAdminMenuUrlRelation::find()
                ->findByLinkId($link_id)
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            $obj = new DpAdminMenuUrlRelation();
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
            $obj = DpAdminMenuUrlRelation::find()
                ->findByLinkId($id)
                ->one();
            if ($obj) {
                $obj->status = DpAdminMenuUrlRelation::STATUS_DELETE;
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
            $obj = DpAdminMenuUrlRelation::find()
                ->findByLinkId($id)
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }

}