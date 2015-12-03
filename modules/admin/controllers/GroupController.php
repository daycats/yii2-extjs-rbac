<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/31
 * Time: 14:02
 */

namespace wsl\rbac\modules\admin\controllers;


use yii\web\Response;
use yii\helpers\StringHelper;
use wsl\rbac\base\Controller;
use wsl\rbac\helpers\ExtHelpers;
use wsl\rbac\models\DpAdminGroup;
use wsl\rbac\models\DpAdminGroupMenuRelation;
use wsl\rbac\models\DpAdminMenu;

/**
 * 用户组
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class GroupController extends Controller
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
            'group_id',
            'name',
            'is_system',
            'note',
            'status'
        ], [], 'group_id DESC');
        $list = DpAdminGroup::getAll($order);

        return [
            'total' => count($list),
            'list' => $list,
        ];
    }

    /**
     * 树形菜单数据
     *
     * @param string $sort 排序字段
     * @return array
     */
    public static function actionMenu($sort = '')
    {
        $group_id = \Yii::$app->request->get('group_id');

        $order = ExtHelpers::getOrder($sort, [
            'menu_id',
            'parent_id',
            'text',
            'title',
            'url',
            'view_package',
            'expanded',
            'closable',
            'is_open_url',
            'is_open_target',
            'is_every_open',
            'is_hide',
            'display_order',
            'note',
            'status',
        ], [], 'menu_id DESC');
        $tree = DpAdminMenu::getGroupMenuTreeByParentId($group_id, 0, $order);

        return [
            'children' => $tree,
        ];
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
        $group_id = \Yii::$app->request->get('group_id');
        $name = \Yii::$app->request->get('name');
        $is_system = \Yii::$app->request->get('is_system');
        $note = \Yii::$app->request->get('note');
        $status = \Yii::$app->request->get('status');
        $limit = intval($limit);
        $order = ExtHelpers::getOrder($sort, [
            'group_id',
            'name',
            'note',
            'status'
        ], [], 'group_id DESC');

        $condition = '1';
        $params = [];
        if ($group_id) {
            $condition .= ' and group_id=:group_id';
            $params[':group_id'] = $group_id;
        }
        if ($name) {
            $condition .= ' and name like :name';
            $params[':name'] = '%' . $name . '%';
        }
        if (is_numeric($is_system)) {
            $condition .= ' and is_system=:is_system';
            $params[':is_system'] = $is_system;
        }
        if ($note) {
            $condition .= ' and note like :note';
            $params[':note'] = '%' . $note . '%';
        }
        if (is_numeric($status)) {
            $condition .= ' and status=:status';
            $params[':status'] = $status;
        }
        $data = DpAdminGroup::getListByCondition($condition, $params, $order, $page, $limit);

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
        $group_id = \Yii::$app->request->post('group_id');
        $menu_ids = \Yii::$app->request->post('menu_ids');
        $name = \Yii::$app->request->post('name');
        $is_system = \Yii::$app->request->post('is_system');
        $note = \Yii::$app->request->post('note');
        $status = \Yii::$app->request->post('status');

        $saveData = [
            'name' => $name,
            'is_system' => $is_system,
            'note' => $note,
            'status' => $status,
        ];
        if ($group_id) {
            $obj = DpAdminGroup::find()
                ->findByGroupId($group_id)
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            $obj = new DpAdminGroup();
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
        // 用户组关联的菜单权限更新
        DpAdminGroupMenuRelation::deleteByGroupId($obj->group_id);
        if ($menu_ids) {
            $menuIdArr = array_filter(StringHelper::explode($menu_ids, ',', true, true), function ($menuId) {
                return DpAdminMenu::getByMenuId($menuId);
            });
            if ($menuIdArr) {
                foreach ($menuIdArr as $menuId) {
                    $linkObj = new DpAdminGroupMenuRelation();
                    $linkObj->setAttributes([
                        'group_id' => $obj->group_id,
                        'menu_id' => $menuId,
                    ]);
                    $linkObj->save();
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
            $obj = DpAdminGroup::find()
                ->findByGroupId($id)
                ->one();
            if ($obj) {
                $obj->status = DpAdminGroup::STATUS_DELETE;
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
            $obj = DpAdminGroup::find()
                ->findByGroupId($id)
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }

}