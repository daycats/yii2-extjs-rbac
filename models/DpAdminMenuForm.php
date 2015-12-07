<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/3
 * Time: 20:13
 */

namespace wsl\rbac\models;


use yii\base\Model;
use yii\helpers\StringHelper;

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
            [['position'], 'in',
                'range' => [
                    static::POSITION_BEFORE,
                    static::POSITION_AFTER,
                    static::POSITION_APPEND,
                ]
            ],
            [['target_menu_id'], 'integer'],
            [['target_menu_id'], 'exist',
                'targetClass' => DpAdminMenu::className(),
                'targetAttribute' => [
                    'target_menu_id' => 'menu_id',
                ],
            ],
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
            $menuModel = DpAdminMenu::find()
                ->findByMenuId($this->target_menu_id)
                ->one();

            // 调整排序的菜单id
            $dropMenuIdArr = array_filter(StringHelper::explode($this->menu_ids, ',', true, true), function ($value) {
                return is_numeric($value) && DpAdminMenu::find()->findByMenuId($value)->exists();
            });
            // 调整排序菜单数量
            $dropCount = count($dropMenuIdArr);
            if (static::POSITION_BEFORE == $this->position) { // 节点之前
                $menuAllObj = DpAdminMenu::find()
                    ->findByParentId($menuModel->parent_id)
                    ->orderBy('display_order asc')
                    ->all();
                // 将要插入的菜单前面的排序向前移动
                // 插入的开始排序号
                $startOrder = $menuModel->display_order - 1;
                $otherStartOrder = $startOrder - $dropCount;
                foreach ($menuAllObj as $menuObj) {
                    if ($menuObj->menu_id == $menuModel->menu_id) {
                        break;
                    }
                    $menuObj->display_order = $otherStartOrder--;
                    $menuObj->save();
                }
                $dropStartOrder = $menuModel->display_order - $dropCount;
                foreach ($dropMenuIdArr as $menuId) {
                    $dropMenu = DpAdminMenu::find()
                        ->findByMenuId($menuId)
                        ->one();
                    if ($dropMenu) {
                        $dropMenu->parent_id = $menuModel->parent_id;
                        $dropMenu->display_order = $dropStartOrder++;
                        $dropMenu->save();
                    }
                }

                return true;
            } elseif (static::POSITION_AFTER == $this->position) { // 节点之后
                // 在菜单后插入
                $menuAllObj = DpAdminMenu::find()
                    ->findByParentId($menuModel->parent_id)
                    ->andWhere('menu_id not in (' . join(',', $dropMenuIdArr) . ')')
                    ->all();
                // 将要插入的菜单后面的排序向后移动
                // 插入的开始排序号
                $isStartOrder = false;
                $startOrder = $menuModel->display_order + 1;
                $otherStartOrder = $startOrder + $dropCount;
                foreach ($menuAllObj as $menuObj) {
                    if ($menuObj->menu_id == $menuModel->menu_id) {
                        $isStartOrder = true;
                        continue;
                    }
                    if (!$isStartOrder) {
                        continue;
                    }
                    $menuObj->display_order = $otherStartOrder++;
                    $menuObj->save();
                }
                foreach ($dropMenuIdArr as $menuId) {
                    $dropMenu = DpAdminMenu::find()
                        ->findByMenuId($menuId)
                        ->one();
                    if ($dropMenu) {
                        $dropMenu->parent_id = $menuModel->parent_id;
                        $dropMenu->display_order = $startOrder++;
                        $dropMenu->save();
                    }
                }

                return true;
            } elseif (static::POSITION_APPEND == $this->position) { // 节点内
                // 添加为子节点
                $maxDisplayOrder = DpAdminMenu::find()
                    ->findByParentId($menuModel->menu_id)
                    ->max('display_order');
                foreach ($dropMenuIdArr as $menuId) {
                    $dropMenu = DpAdminMenu::find()
                        ->findByMenuId($menuId)
                        ->one();
                    if ($dropMenu) {
                        $dropMenu->parent_id = $menuModel->menu_id;
                        $dropMenu->display_order = ++$maxDisplayOrder;
                        $dropMenu->save();
                    }
                }

                return true;
            }
        }

        return false;
    }

}