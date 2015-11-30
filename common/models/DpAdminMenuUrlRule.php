<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_menu_url_rule}}".
 *
 * @property string $rule_id
 * @property string $url_id
 * @property string $param_name
 * @property string $rule
 * @property string $note
 * @property integer $status
 */
class DpAdminMenuUrlRule extends \wsl\db\ActiveRecord
{
    /**
     * 状态 禁用
     */
    const STATUS_DISABLED = 0;
    /**
     * 状态 启用
     */
    const STATUS_ENABLED = 1;
    /**
     * 状态 删除
     */
    const STATUS_DELETE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_menu_url_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_id', 'status'], 'integer'],
            [['url_id', 'param_name', 'rule'], 'required'],
            [['param_name', 'rule', 'note'], 'string', 'max' => 255],
            [['note'], 'default', 'value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_id' => '自增ID',
            'url_id' => '菜单URL id',
            'param_name' => '参数名',
            'rule' => '规则',
            'note' => '备注',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrlRuleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminMenuUrlRuleQuery(get_called_class());
    }

    /**
     * 获取指定路由id记录
     *
     * @param int $ruleId 路由id
     * @return mixed
     */
    public static function getByRuleId($ruleId)
    {
        $data = static::find()
            ->findByRuleId($ruleId)
            ->asArray()
            ->one();

        return static::handlerData($data);
    }

    /**
     * 获取指定url id记录
     *
     * @param int $urlId url id
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
     * 获取指定url id所有记录
     *
     * @param int $urlId url id
     * @return mixed
     */
    public static function getAllByUrlId($urlId)
    {
        $data = static::find()
            ->findByUrlId($urlId)
            ->asArray()
            ->all();

        return static::handlerListData($data);
    }
}
