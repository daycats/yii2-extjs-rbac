<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/12/3
 * Time: 20:13
 */

namespace wsl\rbac\models;


use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * 菜单表单模型
 *
 * @package wsl\rbac\models
 */
class DpAdminMenuForm extends Model
{
    /**
     * 场景 更新排序
     */
    const SCENARIO_UPDATE_SORT = 'updateSort';

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
            [['position'], 'string'],
            [['menu_ids'], 'each', 'rule' => ['integer']],
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
            static::SCENARIO_UPDATE_SORT => ['menu_ids', 'position', 'target_menu_id'],
        ];
    }

    /**
     * 更新排序
     *
     * @return bool
     */
    public function updateSort()
    {
        if ($this->validate()) {
            $srcMenuModels = DpAdminMenu::find()
                ->andFilterWhere(['in', 'menu_id', $this->menu_ids])
                ->orderBy([new Expression('field(menu_id, ' . join(',', $this->menu_ids) . ')')])
                ->all();
            $targetMenuModel = DpAdminMenu::find()
                ->findByMenuId($this->target_menu_id)
                ->one();
            $menuModels = DpAdminMenu::find()
                ->findByParentId($targetMenuModel->parent_id)
                ->defaultOrder()
                ->all();
            $srcMenuIds = ArrayHelper::getColumn($srcMenuModels, 'menu_id');
            if ($srcMenuModels && $targetMenuModel && $menuModels) {
                if (static::POSITION_BEFORE == $this->position) {
                    $displayOrder = -1;
                    $transaction = Yii::$app->db->beginTransaction();
                    foreach ($menuModels as $itemMenuModel) {
                        if (in_array($itemMenuModel->menu_id, $srcMenuIds)) {
                            continue;
                        }
                        $displayOrder++;
                        if ($targetMenuModel->menu_id == $itemMenuModel->menu_id) {
                            // before
                            foreach ($srcMenuModels as $itemSrcMenuMode) {
                                $itemSrcMenuMode->display_order = $displayOrder;
                                $itemSrcMenuMode->parent_id = $targetMenuModel->parent_id;
                                if (!$itemSrcMenuMode->save()) {
                                    $transaction->rollBack();
                                    $this->addErrors($itemSrcMenuMode->errors);
                                    return false;
                                }
                                $displayOrder++;
                            }
                            // target
                            $targetMenuModel->display_order = $displayOrder;
                            if (!$targetMenuModel->save()) {
                                $transaction->rollBack();
                                $this->addErrors($targetMenuModel->errors);
                                return false;
                            }
                            continue;
                        }
                        $itemMenuModel->display_order = $displayOrder;
                        if (!$itemMenuModel->save()) {
                            $transaction->rollBack();
                            $this->addErrors($itemMenuModel->errors);
                            return false;
                        }
                    }
                    $transaction->commit();
                    return true;
                } elseif (static::POSITION_AFTER == $this->position) {
                    $displayOrder = -1;
                    $transaction = Yii::$app->db->beginTransaction();
                    foreach ($menuModels as $itemMenuModel) {
                        if (in_array($itemMenuModel->menu_id, $srcMenuIds)) {
                            continue;
                        }
                        $displayOrder++;
                        if ($targetMenuModel->menu_id == $itemMenuModel->menu_id) {
                            // target
                            $targetMenuModel->display_order = $displayOrder;
                            if (!$targetMenuModel->save()) {
                                $transaction->rollBack();
                                $this->addErrors($targetMenuModel->errors);
                                return false;
                            }
                            $displayOrder++;
                            // after
                            foreach ($srcMenuModels as $itemSrcMenuMode) {
                                $itemSrcMenuMode->display_order = $displayOrder;
                                $itemSrcMenuMode->parent_id = $targetMenuModel->parent_id;
                                if (!$itemSrcMenuMode->save()) {
                                    $transaction->rollBack();
                                    $this->addErrors($itemSrcMenuMode->errors);
                                    return false;
                                }
                                $displayOrder++;
                            }
                            continue;
                        }
                        $itemMenuModel->display_order = $displayOrder;
                        if (!$itemMenuModel->save()) {
                            $transaction->rollBack();
                            $this->addErrors($itemMenuModel->errors);
                            return false;
                        }
                    }
                    $transaction->commit();
                    return true;
                } elseif (static::POSITION_APPEND == $this->position) {
                    $menuModels = DpAdminMenu::find()
                        ->findByParentId($targetMenuModel->menu_id)
                        ->defaultOrder()
                        ->all();
                    if ($menuModels) {
                        // 将目标菜单设置成该目标元素下的最后一个元素
                        $targetParentMenuModels = DpAdminMenu::find()->findByParentId($targetMenuModel->menu_id)->defaultOrder()->all();
                        $targetMenuModel = array_pop($targetParentMenuModels);

                        $displayOrder = -1;
                        $transaction = Yii::$app->db->beginTransaction();
                        foreach ($menuModels as $itemMenuModel) {
                            if (in_array($itemMenuModel->menu_id, $srcMenuIds)) {
                                continue;
                            }
                            $displayOrder++;
                            if ($targetMenuModel->menu_id == $itemMenuModel->menu_id) {
                                // target
                                $targetMenuModel->display_order = $displayOrder;
                                if (!$targetMenuModel->save()) {
                                    $transaction->rollBack();
                                    $this->addErrors($targetMenuModel->errors);
                                    return false;
                                }
                                $displayOrder++;
                                // after
                                foreach ($srcMenuModels as $itemSrcMenuMode) {
                                    $itemSrcMenuMode->display_order = $displayOrder;
                                    $itemSrcMenuMode->parent_id = $targetMenuModel->parent_id;
                                    if (!$itemSrcMenuMode->save()) {
                                        $transaction->rollBack();
                                        $this->addErrors($itemSrcMenuMode->errors);
                                        return false;
                                    }
                                    $displayOrder++;
                                }
                                continue;
                            }
                            $itemMenuModel->display_order = $displayOrder;
                            if (!$itemMenuModel->save()) {
                                $transaction->rollBack();
                                $this->addErrors($itemMenuModel->errors);
                                return false;
                            }
                        }
                    } else {
                        $displayOrder = 0;
                        // 菜单没有节点时
                        $transaction = Yii::$app->db->beginTransaction();
                        foreach ($srcMenuModels as $itemSrcMenuMode) {
                            $itemSrcMenuMode->display_order = $displayOrder;
                            $itemSrcMenuMode->parent_id = $targetMenuModel->menu_id;
                            if (!$itemSrcMenuMode->save()) {
                                $transaction->rollBack();
                                $this->addErrors($itemSrcMenuMode->errors);
                                return false;
                            }
                            $displayOrder++;
                        }
                    }
                    $transaction->commit();
                    return true;
                }
            }
        }

        return false;
    }

}