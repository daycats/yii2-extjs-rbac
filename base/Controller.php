<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/2
 * Time: 14:09
 */

namespace wsl\rbac\base;


use wsl\rbac\models\DpAdminGroup;
use wsl\rbac\models\DpAdminMenuUrl;
use wsl\rbac\models\DpAdminUserMenuRelation;
use wsl\rbac\models\DpConfig;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\web\Response;

/**
 * 基础控制器
 *
 * @package wsl\rbac\base
 */
class Controller extends \yii\web\Controller
{
    /**
     * extjs路径
     *
     * @var string
     */
    public $extJsDir = '@bower/dp-extjs';
    /**
     * extjs扩展路径
     *
     * @var string
     */
    public $extJsExtendDir = '@bower/dp-extjs-extend';
    /**
     * 目标符号连接路径
     *
     * @var string
     */
    public $dstPath = '@app/web/dp';

    public $maxLimit = 200;

    public $format = null;

    public $config = [];
    /**
     * @var \wsl\rbac\models\DpAdminUser
     */
    public $identity;
    /**
     * @var array 允许使用的菜单id列表
     */
    public $menuIdList = [];

    public function init()
    {
        parent::init();

        $extJsSrcDir = Yii::getAlias($this->extJsDir);
        $extJsSrcExtendDir = Yii::getAlias($this->extJsExtendDir);
        $dstDir = Yii::getAlias($this->dstPath);
        $extJsDstDir = $dstDir . DIRECTORY_SEPARATOR . 'extjs';
        $extJsExtendDstDir = $dstDir . DIRECTORY_SEPARATOR . 'extjs-extend';
        if (!is_dir($dstDir)) {
            @rmdir($dstDir);
            if (!is_dir($dstDir)) {
                FileHelper::createDirectory($dstDir);
            }
        }
        if (!is_dir($extJsDstDir)) {
            symlink($extJsSrcDir, $extJsDstDir);
        }
        if (!is_dir($extJsExtendDstDir)) {
            symlink($extJsSrcExtendDir, $extJsExtendDstDir);
        }

        $data = DpConfig::find()->all();
        $config = [];
        foreach ($data as $item) {
            $config[$item['name']] = $item['value'];
        }
        $this->config = $config;
        $this->identity = Yii::$app->user->identity;
    }

    public function beforeAction($action)
    {
        $route = Yii::$app->requestedRoute;
        if ($this->identity) {
            if ($this->identity->is_super) {
                $allowAccess = true;
            } else {
                // 权限验证
                $userMenuIdList = [];
                $groupMenuIdList = [];
                if ($this->identity->is_user_access) { // 用户权限列表
                    $userMenuIdList = DpAdminUserMenuRelation::getAllMenuIdArrByUserId($this->identity->user_id);
                }
                if ($this->identity->is_group_access) { // 用户组权限列表
                    $groupMenuIdList = DpAdminGroup::getMenuIdArrByGroupIdArr($this->identity->getGroupIdArr());
                }
                $this->menuIdList = array_merge($userMenuIdList, $groupMenuIdList);
                $routeWhiteList = [
                    '',
                    'admin/common/tree',
                    'admin/common/urls',
                    'admin/public/logout',
                ];
                $allowAccess = in_array($route, $routeWhiteList);
                if (!$allowAccess) {
                    $queryParams = Yii::$app->request->queryParams;
                    $method = Yii::$app->request->method;
                    $urlRule = DpAdminMenuUrl::getUrlRuleByMenuIdArr($this->menuIdList);
                    $allowAccess = !!array_filter($urlRule, function ($item) use ($route, $queryParams, $method) {
                        if (strpos($item['route'], '/') === 0) {
                            $ruleRoute = substr($item['route'], 1, strlen($item['route']));
                        } else {
                            $ruleRoute = $item['route'];
                        }
                        if ($ruleRoute == $route) {
                            // 请求方法验证
                            if (!in_array($method, StringHelper::explode($item['method'], ',', true, true))) {
                                return false;
                            }
                            if ($item['enable_rule']) {
                                // get参数规则验证
                                foreach ($queryParams as $qk => $qv) {
                                    if (isset($item['rule'][$qk])) {
                                        $pattern = '/' . $item['rule'][$qk] . '/';
                                        if (preg_match($pattern, $qv)) {
                                            return true;
                                        }
                                    }
                                }

                                return false;
                            } else {
                                return true;
                            }
                        }
                        return false;
                    });
                }
            }
            if (!$allowAccess) {
                // 权限不足
                $response = Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                $response->data = [
                    'success' => false,
                    'msg' => '权限不足',
                    'code' => 2,
                ];
                return false;
            } else {
                return parent::beforeAction($action);
            }
        } else {
            $routeWhiteList = [
                '',
                'admin/public/login',
                'admin/public/logout',
            ];
            $allowAccess = in_array($route, $routeWhiteList);
            if (!$allowAccess) {
                // 未登录
                $response = Yii::$app->response;
                $response->format = Response::FORMAT_JSON;
                $response->data = [
                    'success' => false,
                    'msg' => '请先登录系统',
                    'code' => 1,
                ];
                return false;
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * 获取配置信息
     *
     * @param string $name 配置名
     * @return string|null
     */
    public function getConfig($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    public function afterAction($action, $result)
    {
        if (Response::FORMAT_JSON == $this->format) {
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            $response->data = $result;
        }

        return parent::afterAction($action, $result);
    }

    /**
     * 成功信息
     *
     * @param string $msg 消息
     * @param array|null $data 数据
     * @return array
     */
    public function renderSuccess($msg, $data = null)
    {
        $return = [
            'success' => true,
            'msg' => $msg,
        ];
        if (!is_null($data)) {
            $return['data'] = $data;
        }

        return $return;
    }

    /**
     * 失败信息
     *
     * @param string $msg 消息
     * @param array|null $data 数据
     * @return array
     */
    public function renderError($msg, $data = null)
    {
        $return = [
            'success' => false,
            'msg' => $msg,
        ];
        if (!is_null($data)) {
            $return['data'] = $data;
        }

        return $return;
    }

    /**
     * 响应json数据
     *
     * @param array $data 输出json数据
     * @return array
     */
    public function responseJson($data)
    {
        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;

        return $data;
    }

    /**
     * 输出消息
     *
     * @param int $code 返回码
     * @param string $msg 消息
     * @param array|null $data 数据
     * @param array $extraData 额外的数据只覆盖对应的key
     * @return array
     */
    public function output($code, $msg = null, $data = null, $extraData = [])
    {
        if (is_null($msg)) {
            $msg = '未知';
        }

        $return = [
            'success' => (boolean)$code,
            'msg' => $msg,
        ];
        if ($extraData) {
            $return = array_merge($return, $extraData);
        }
        if (!is_null($data)) {
            $return['data'] = $data;
        }

        return $this->responseJson($return);
    }

    /**
     * 成功信息
     *
     * @param string $msg 消息
     * @param array|null $data 数据
     * @param array $extraData 额外的数据只覆盖对应的key
     * @return array
     */
    public function success($msg = null, $data = null, $extraData = [])
    {
        $return = $this->output(1, $msg, $data, $extraData);

        return $this->responseJson($return);
    }

    /**
     * 成功输出数据
     *
     * @param array|null $data 数据
     * @param string $msg 消息
     * @param array $extraData 额外的数据只覆盖对应的key
     * @return array
     */
    public function successData($data, $msg = null, $extraData = [])
    {
        $return = $this->output(1, $msg, $data, $extraData);

        return $this->responseJson($return);
    }

    /**
     * 失败信息
     *
     * @param string $msg 消息
     * @param array|null $data 数据
     * @param array $extraData 额外的数据只覆盖对应的key
     * @return array
     */
    public function error($msg = null, $data = null, $extraData = [])
    {
        $return = $this->output(0, $msg, $data, $extraData);

        return $this->responseJson($return);
    }

}