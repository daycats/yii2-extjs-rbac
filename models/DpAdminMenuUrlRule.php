<?php

namespace wsl\rbac\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dp_admin_menu_url_rule".
 *
 * @property string $rule_id
 * @property string $url_id
 * @property string $param_name
 * @property string $rule
 * @property string $note
 * @property integer $status
 */
class DpAdminMenuUrlRule extends ActiveRecord
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
        return 'dp_admin_menu_url_rule';
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
        $find = new DpAdminMenuUrlRuleQuery(get_called_class());
        $find->normal();

        return $find;
    }

}
