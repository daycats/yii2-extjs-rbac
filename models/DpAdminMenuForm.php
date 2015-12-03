<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/3
 * Time: 20:13
 */

namespace wsl\rbac\models;


use yii\base\Model;

/**
 * 菜单表单模型
 *
 * @package wsl\rbac\models
 */
class DpAdminMenuForm extends Model
{
    /**
     * 拖拽排序
     */
    const SCENARIO_DRAG_SORT = 'dragSort';

    /**
     * 位置 之前
     */
    const POSITION_BEFORE = 'before';
    /**
     * 位置 之后
     */
    const POSITION_AFTER = 'after';
    /**
     * 位置 追加
     */
    const POSITION_APPEND = 'append';

    /**
     * @var string 菜单id集
     */
    public $menu_ids;
    /**
     * @var string 位置
     */
    public $position;
    /**
     * @var int 目标菜单id
     */
    public $target_menu_id;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['menu_ids', 'position', 'target_menu_id'], 'required'],
            [['menu_ids', 'position'], 'string'],
            [['target_menu_id'], 'integer'],
            [['target_menu_id'], 'exist', 'targetClass' => DpAdminMenu::className()],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'menu_ids' => '菜单id集',
            'position' => '位置',
            'target_menu_id' => '目标菜单id',
        ];
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            static::SCENARIO_DRAG_SORT => ['menu_ids', 'position', 'target_menu_id'],
        ];
    }

    /**
     * 拖拽排序
     *
     * @return bool
     */
    public function dragSort()
    {
        if ($this->validate()) {
            if (static::POSITION_BEFORE) {

            }
        }

        return false;
    }

}