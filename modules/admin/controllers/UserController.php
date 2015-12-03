<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/23
 * Time: 11:01
 */

namespace wsl\rbac\modules\admin\controllers;


use wsl\rbac\base\Controller;
use wsl\rbac\helpers\ExtHelpers;
use wsl\rbac\models\DpAdminGroup;
use wsl\rbac\models\DpAdminMenu;
use wsl\rbac\models\DpAdminUser;
use wsl\rbac\models\DpAdminUserMenuRelation;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 管理员用户控制器
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class UserController extends Controller
{
    public $format = Response::FORMAT_JSON;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 树形菜单数据
     *
     * @param string $sort 排序字段
     * @return array
     */
    public static function actionMenu($sort = '')
    {
        $user_id = \Yii::$app->request->get('user_id');

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
        $tree = DpAdminMenu::getUserMenuTreeByParentId($user_id, 0, $order);

        return [
            'children' => $tree,
        ];
    }

    /**
     * 列表
     *
     * @param string $sort 排序
     * @param int $page 页码
     * @param int $limit 每页记录数
     * @return array
     */
    public function actionList($sort = '', $page = 1, $limit = 20)
    {
        $user_id = \Yii::$app->request->get('user_id');
        $username = \Yii::$app->request->get('username');
        $nickname = \Yii::$app->request->get('nickname');
        $group_ids = array_filter((array)\Yii::$app->request->get('group_ids'));
        $is_group_access = \Yii::$app->request->get('is_group_access');
        $is_user_access = \Yii::$app->request->get('is_user_access');
        $is_system = \Yii::$app->request->get('is_system');
        $note = \Yii::$app->request->get('note');
        $status = \Yii::$app->request->get('status');
        $limit = intval($limit);
        $order = ExtHelpers::getOrder($sort, [
            'user_id',
            'username',
            'nickname',
            'is_system',
            'note',
            'status'
        ], [], 'user_id DESC');

        $condition = '1';
        $params = [];
        if ($user_id) {
            $condition .= ' and user_id=:user_id';
            $params[':user_id'] = $user_id;
        }
        if ($username) {
            $condition .= ' and username=:username';
            $params[':username'] = $username;
        }
        if (is_numeric($is_group_access)) {
            $condition .= ' and is_group_access=:is_group_access';
            $params[':is_group_access'] = $is_group_access;
        }
        if (is_numeric($is_user_access)) {
            $condition .= ' and is_user_access=:is_user_access';
            $params[':is_user_access'] = $is_user_access;
        }
        if (is_numeric($is_system)) {
            $condition .= ' and is_system=:is_system';
            $params[':is_system'] = $is_system;
        }
        if ($note) {
            $condition .= ' and note like :note';
            $params[':note'] = '%' . $note . '%';
        }
        if ($nickname) {
            $condition .= ' and nickname like :nickname';
            $params[':nickname'] = '%' . $nickname . '%';
        }
        if ($group_ids && is_array($group_ids)) {
            $group_ids = array_filter($group_ids, function ($value) {
                return is_numeric($value);
            });
            $condition .= ' and group_ids in (' . join(',', $group_ids) . ')';
        }
        if (is_numeric($status)) {
            $condition .= ' and status=:status';
            $params[':status'] = $status;
        }
        $condition .= ' and user_id!=1';
        $data = DpAdminUser::getListByCondition($condition, $params, $order, $page, $limit);
        $list = [];
        if ($data['list']) {
            foreach ($data['list'] as $item) {
                $groupIds = StringHelper::explode($item['group_ids'], ',', true, true);
                asort($groupIds);
                $groupNames = [];
                foreach ($groupIds as $groupId) {
                    $group = DpAdminGroup::getByGroupId($groupId);
                    if ($group) {
                        $groupNames[] = $group['name'];
                    }
                }
                $list[] = [
                    'user_id' => $item['user_id'],
                    'username' => $item['username'],
                    'nickname' => $item['nickname'],
                    'group_ids' => join(',', $groupIds),
                    'group_names' => join(',', $groupNames),
                    'is_group_access' => $item['is_group_access'],
                    'is_user_access' => $item['is_user_access'],
                    'is_system' => $item['is_system'],
                    'note' => $item['note'],
                    'status' => $item['status'],
                ];
            }
        }

        return [
            'total' => $data['pagination']['totalCount'],
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
        $user_id = \Yii::$app->request->post('user_id');
        $menu_ids = \Yii::$app->request->post('menu_ids');
        $username = \Yii::$app->request->post('username');
        $nickname = \Yii::$app->request->post('nickname');
        $source_password = \Yii::$app->request->post('source_password');
        $group_ids = (array)\Yii::$app->request->post('group_ids');
        $is_group_access = \Yii::$app->request->post('is_group_access');
        $is_user_access = \Yii::$app->request->post('is_user_access');
        $is_system = \Yii::$app->request->post('is_system');
        $note = \Yii::$app->request->post('note');
        $status = \Yii::$app->request->post('status');

        $groupIdsStr = '';
        if ($group_ids) {
            $groupIdsStr = join(',', array_filter($group_ids, function ($groupId) {
                return DpAdminGroup::getByGroupId($groupId);
            }));
        }

        $saveData = [
            'username' => $username,
            'nickname' => $nickname,
            'source_password' => $source_password,
            'group_ids' => $groupIdsStr,
            'is_group_access' => $is_group_access,
            'is_user_access' => $is_user_access,
            'is_system' => $is_system,
            'note' => $note,
            'status' => $status,
        ];
        if ($user_id) {
            $obj = DpAdminUser::find()
                ->where([
                    'user_id' => $user_id
                ])
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            $obj = new DpAdminUser();
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
        // 用户关联的菜单权限更新
        DpAdminUserMenuRelation::deleteByUserId($obj->user_id);
        if ($menu_ids) {
            $menuIdArr = array_filter(StringHelper::explode($menu_ids, ',', true, true), function ($menuId) {
                return DpAdminMenu::getByMenuId($menuId);
            });
            if ($menuIdArr) {
                foreach ($menuIdArr as $menuId) {
                    $linkObj = new DpAdminUserMenuRelation();
                    $linkObj->setAttributes([
                        'user_id' => $obj->user_id,
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
            $obj = DpAdminUser::find()
                ->andWhere(['user_id' => $id])
                ->one();
            if ($obj) {
                $obj->status = DpAdminUser::STATUS_DELETE;
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
            $obj = DpAdminUser::find()
                ->andWhere(['user_id' => $id])
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }
}