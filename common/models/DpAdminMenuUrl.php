<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_menu_url}}".
 *
 * @property string $url_id
 * @property string $name
 * @property string $alias
 * @property string $route
 * @property string $method
 * @property string $host
 * @property integer $enable_rule
 * @property integer $note
 * @property integer $status
 */
class DpAdminMenuUrl extends \wsl\db\ActiveRecord
{
    /**
     * 状态 禁用
     */
    const STATUS_INACTIVE = 0;
    /**
     * 状态 启用
     */
    const STATUS_ACTIVE = 1;
    /**
     * 状态 删除
     */
    const STATUS_DELETE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_menu_url}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'route'], 'required'],
            [['enable_rule', 'status'], 'integer'],
            [['name', 'alias', 'route', 'method', 'host', 'note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url_id' => '自增ID',
            'name' => '名称',
            'alias' => '别名',
            'route' => '路由',
            'method' => '请求方式',
            'host' => '主机地址',
            'enable_rule' => '启用规则',
            'note' => '备注',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrlQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminMenuUrlQuery(get_called_class());
    }

    /**
     * 获取指定URL id记录
     *
     * @param int $urlId URL id
     * @return mixed
     */
    public static function getByUrlId($urlId)
    {
        $data = static::find()
            ->findByUrlId($urlId)
            ->asArray()
            ->one();

        return static::handlerData($data);
    }

    /**
     * 获取指定菜单id数组url规则记录
     *
     * @param array $menuIdArr 菜单id数组
     * @return array
     */
    public static function getUrlRuleByMenuIdArr($menuIdArr)
    {
        $urlRuls = [];
        $urlIdArr = DpAdminMenuUrlLink::getUrlIdArrByMenuIdArr($menuIdArr);
        foreach ($urlIdArr as $urlId) {
            $url = static::find()
                ->findByUrlId($urlId)
                ->asArray()
                ->one();
            if ($url) {
                $urlRuleAll = DpAdminMenuUrlRule::getAllByUrlId($urlId);
                $rule = [];
                foreach ($urlRuleAll as $item) {
                    $rule[$item['param_name']] = $item['rule'];
                }
                $urlRuls[] = [
                    'route' => $url['route'],
                    'method' => $url['method'],
                    'enable_rule' => $url['enable_rule'],
                    'rule' => $rule,
                ];
            }
        }

        return $urlRuls;
    }
}
