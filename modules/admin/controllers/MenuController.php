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
use wsl\rbac\models\DpAdminGroup;
use wsl\rbac\models\DpAdminGroupMenuRelation;
use wsl\rbac\models\DpAdminMenu;
use wsl\rbac\models\DpAdminMenuForm;
use wsl\rbac\models\DpAdminUser;
use wsl\rbac\models\DpAdminUserMenuRelation;
use Yii;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 菜单
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class MenuController extends Controller
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
    public static function actionTree($sort = '')
    {
        $order = ExtHelpers::getOrder($sort, [
            'menu_id',
            'parent_id',
            'text',
            'title',
            'url',
            'view_package',
            'expanded',
            'closable',
            'is_folder',
            'is_open_url',
            'is_open_target',
            'is_every_open',
            'is_hide',
            'display_order',
            'note',
            'status',
        ], [], 'display_order ASC,menu_id ASC');
        $tree = DpAdminMenu::getTreeByParentId(null, '', [], $order);

        return [
            'children' => $tree,
        ];
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
            'menu_id',
            'parent_id',
            'text',
            'title',
            'url',
            'view_package',
            'expanded',
            'closable',
            'is_folder',
            'is_open_url',
            'is_open_target',
            'is_every_open',
            'is_hide',
            'display_order',
            'note',
            'status',
        ], [], 'display_order ASC,menu_id ASC');
        $list = DpAdminMenu::find()->orderBy($order)->all();

        return [
            'total' => count($list),
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
        $menu_id = Yii::$app->request->post('menu_id');
        $parent_id = intval(Yii::$app->request->post('parent_id'));
        $text = Yii::$app->request->post('origin_text');
        $title = Yii::$app->request->post('title');
        $url = Yii::$app->request->post('url');
        $view_package = Yii::$app->request->post('view_package');
        $expanded = intval(Yii::$app->request->post('is_expand'));
        $closable = intval(Yii::$app->request->post('closable'));
        $is_folder = intval(Yii::$app->request->post('is_folder'));
        $is_open_url = intval(Yii::$app->request->post('is_open_url'));
        $is_open_target = intval(Yii::$app->request->post('is_open_target'));
        $is_every_open = intval(Yii::$app->request->post('is_every_open'));
        $is_hide = intval(Yii::$app->request->post('is_hide'));
        $display_order = Yii::$app->request->post('display_order');
        $params = Yii::$app->request->post('params');
        $note = Yii::$app->request->post('note');
        $status = intval(Yii::$app->request->post('status'));

        $parent_id = $parent_id ? $parent_id : null;

        if ($parent_id) {
            if ($parent_id == $menu_id) {
                return $this->renderError('不能把自己当作父级');
            } else {
                $menu = DpAdminMenu::find()
                    ->findByMenuId($parent_id)
                    ->asArray()
                    ->one();;
                if (!$menu) {
                    return $this->renderError('父级不存在');
                }
            }
        }

        $saveData = [
            'parent_id' => $parent_id,
            'text' => $text,
            'title' => $title,
            'url' => $url,
            'view_package' => $view_package,
            'expanded' => $expanded,
            'closable' => $closable,
            'is_folder' => $is_folder,
            'is_open_url' => $is_open_url,
            'is_open_target' => $is_open_target,
            'is_every_open' => $is_every_open,
            'is_hide' => $is_hide,
            'display_order' => $display_order,
            'params' => $params,
            'note' => $note,
            'status' => $status,
        ];
        if ($menu_id) {
            $obj = DpAdminMenu::find()
                ->findByMenuId($menu_id)
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            $obj = new DpAdminMenu();
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

        // 自动给系统用户添加菜单权限
        if (!$menu_id) {
            $users = DpAdminUser::find()
                ->findByIsSystem(1)
                ->asArray()
                ->all();
            foreach ($users as $user) {
                $relationExists = DpAdminUserMenuRelation::find()->andWhere([
                    'user_id' => $user['user_id'],
                    'menu_id' => $obj->menu_id,
                ])->exists();
				if (!$relationExists) {
					$linkObj = new DpAdminUserMenuRelation();
					$linkObj->setAttributes([
						'user_id' => $user['user_id'],
						'menu_id' => $obj->menu_id,
					]);
					$linkObj->save();
				}
            }
        }
        // 自动给系统用户组添加菜单权限
        if (!$menu_id) {
            $groups = DpAdminGroup::find()
                ->findByIsSystem(1)
                ->asArray()
                ->all();
            foreach ($groups as $group) {
                $relationExists = DpAdminGroupMenuRelation::find()->andWhere([
					'group_id' => $group['group_id'],
					'menu_id' => $obj->menu_id,
                ])->exists();
				if (!$relationExists) {
					$linkObj = new DpAdminGroupMenuRelation();
					$linkObj->setAttributes([
						'group_id' => $group['group_id'],
						'menu_id' => $obj->menu_id,
					]);
					$linkObj->save();
				}
            }
        }

        return $this->renderSuccess('保存成功');
    }

    /**
     * 菜单拖放更改排序
     *
     * @return array
     */
    public function actionDrop()
    {
        $formModel = new DpAdminMenuForm();
        $formModel->setScenario($formModel::SCENARIO_UPDATE_SORT);
        if ($formModel->load(Yii::$app->request->post(), '')) {
            if ($formModel->updateSort()) {
                return $this->renderSuccess('排序调整成功');
            }
            foreach ($formModel->errors as $field) {
                foreach ($field as $message) {
                    return $this->renderError($message);
                }
            }
        }

        return $this->renderError('未知错误');
    }

    /**
     * 删除(设置一个删除的标识)
     *
     * @return array
     */
    public function actionDel()
    {
        $ids = Yii::$app->request->post('ids');
        foreach (StringHelper::explode($ids, ',', true, true) as $id) {
            $obj = DpAdminMenu::find()
                ->findByMenuId($id)
                ->one();
            if ($obj) {
                $obj->status = DpAdminMenu::STATUS_DELETE;
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
        $ids = Yii::$app->request->post('ids');
        $status = intval(Yii::$app->request->post('status'));
        if ($status != 0) {
            $status = 1;
        }
        foreach (StringHelper::explode($ids, ',', true, true) as $id) {
            $obj = DpAdminMenu::find()
                ->findByMenuId($id)
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }

}
