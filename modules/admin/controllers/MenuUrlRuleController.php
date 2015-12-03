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
use wsl\rbac\models\DpAdminMenuUrlRule;
use wsl\rbac\models\search\DpAdminMenuUrlRuleSearch;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 菜单URL规则
 *
 * @package wsl\rbac\modules\admin\controllers
 */
class MenuUrlRuleController extends Controller
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
     * @return array
     */
    public function actionList($sort = '')
    {
        $order = ExtHelpers::getOrder($sort, [
            'rule_id',
            'route',
            'method',
            'host',
            'enable_rule',
            'note',
            'status'
        ], [], 'rule_id DESC');

        $list = [];

        $searchModel = new DpAdminMenuUrlRuleSearch();
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
        $rule_id = \Yii::$app->request->post('rule_id');
        $url_id = \Yii::$app->request->post('url_id');
        $param_name = \Yii::$app->request->post('param_name');
        $rule = \Yii::$app->request->post('rule');
        $note = \Yii::$app->request->post('note');
        $status = \Yii::$app->request->post('status');

        if (!DpAdminMenuUrl::getByUrlId($url_id)) {
            return $this->renderError('菜单URL不存在！');
        }
        $saveData = [
            'url_id' => $url_id,
            'param_name' => $param_name,
            'rule' => $rule,
            'note' => $note,
            'status' => $status,
        ];
        if ($rule_id) {
            $obj = DpAdminMenuUrlRule::find()
                ->findByRuleId($rule_id)
                ->one();
            if (!$obj) {
                return $this->renderError('保存失败，记录不存在！');
            }
        } else {
            $obj = new DpAdminMenuUrlRule();
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
            $obj = DpAdminMenuUrlRule::find()
                ->findByRuleId($id)
                ->one();
            if ($obj) {
                $obj->status = DpAdminMenuUrlRule::STATUS_DELETE;
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
            $obj = DpAdminMenuUrlRule::find()
                ->findByRuleId($id)
                ->one();
            if ($obj) {
                $obj->status = $status;
                $obj->save();
            }
        }

        return $this->renderSuccess('状态更新成功');
    }

}