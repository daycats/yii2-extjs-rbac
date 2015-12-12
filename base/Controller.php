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
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\web\Response;

/**
 * 基础控制器
 *
 * @package wsl\rbac\base
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var string extjs路径
     */
    protected $srcExtJsDir = '@bower/dp-extjs';
    /**
     * @var string extjs扩展路径
     */
    protected $srcExtJsExtendDir = '@bower/dp-extjs-extend';
    /**
     * @var array ExtJs配置
     *
     *```php
     * [
     *  'path' => '', // ExtJs符号连接路径
     *  'extendPath' => '', // ExtJs扩展符号连接路径
     *  'appJsPath' => '', // app.js路径
     *  'bootstrapJsPath' => '', // bootstrap.js路径
     *  'bootstrapJsonPath' => '', // bootstrap.json路径
     *  'bootstrapCssPath' => '', // bootstrap.css路径
     * ]
     *```
     */
    public $extJs = [];

    /**
     * @var int 最大页码限制
     */
    public $maxLimit = 200;
    /**
     * @var string 输出数据格式化类型
     */
    public $format = null;
    /**
     * @var array 系统配置
     */
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
        // 默认配置
        $this->extJs['path'] = ArrayHelper::getValue($this->extJs, 'path', '/dp/extjs');
        $this->extJs['extendPath'] = ArrayHelper::getValue($this->extJs, 'extendPath', '/dp/extjs-extend');
        $this->extJs['appJsPath'] = ArrayHelper::getValue($this->extJs, 'appJsPath', $this->extJs['extendPath'] . '/app.js');
        $this->extJs['bootstrapJsPath'] = ArrayHelper::getValue($this->extJs, 'bootstrapJsPath', $this->extJs['extendPath'] . '/bootstrap.js');
        $this->extJs['bootstrapJsonPath'] = ArrayHelper::getValue($this->extJs, 'bootstrapJsonPath', $this->extJs['extendPath'] . '/bootstrap.json');
        $this->extJs['bootstrapCssPath'] = ArrayHelper::getValue($this->extJs, 'bootstrapCssPath', $this->extJs['path'] . '/packages/ext-theme-crisp/build/resources/ext-theme-crisp-all.css');
        // js路径
        $extJsSrcDir = Yii::getAlias($this->srcExtJsDir);
        $extJsSrcExtendDir = Yii::getAlias($this->srcExtJsExtendDir);
        $extJsDstDir = Yii::getAlias('@app/web/' . $this->extJs['path']);
        $extJsExtendDstDir = Yii::getAlias('@app/web/' . $this->extJs['extendPath']);
        // 创建符号连接所需的目录
        $extJsDstUpDir = dirname($extJsDstDir);
        if (!is_dir($extJsDstUpDir)) {
            @rmdir($extJsDstUpDir);
            if (!is_dir($extJsDstUpDir)) {
                FileHelper::createDirectory($extJsDstUpDir);
            }
        }
        $extJsExtendDstUpDir = dirname($extJsExtendDstDir);
        if (!is_dir($extJsExtendDstUpDir)) {
            @rmdir($extJsExtendDstUpDir);
            if (!is_dir($extJsExtendDstUpDir)) {
                FileHelper::createDirectory($extJsExtendDstUpDir);
            }
        }
        // js目录创建符号连接
        if (!is_dir($extJsDstDir)) {
            @rmdir($extJsDstDir);
            symlink($extJsSrcDir, $extJsDstDir);
        }
        if (!is_dir($extJsExtendDstDir)) {
            @rmdir($extJsExtendDstDir);
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