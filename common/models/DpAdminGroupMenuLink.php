<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_group_menu_link}}".
 *
 * @property integer $group_id
 * @property integer $menu_id
 */
class DpAdminGroupMenuLink extends \wsl\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_group_menu_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'menu_id'], 'required'],
            [['group_id', 'menu_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => '分组id',
            'menu_id' => '菜单id',
        ];
    }

    /**
     * @inheritdoc
     * @return DpAdminGroupMenuLinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminGroupMenuLinkQuery(get_called_class());
    }

    /**
     * 获取指定用户组id记录
     *
     * @param $groupId
     * @return mixed
     */
    public static function getByGroupId($groupId)
    {
        $data = static::find()
            ->findByGroupId($groupId)
            ->asArray()
            ->one();

        return static::handlerData($data);
    }

    /**
     * 获取指定用户组id记录
     *
     * @param $menuId
     * @return mixed
     */
    public static function getByMenuId($menuId)
    {
        $data = static::find()
            ->findByMenuId($menuId)
            ->asArray()
            ->one();

        return static::handlerData($data);
    }

    /**
     * 获取指定用户组id所有记录
     *
     * @param $groupId
     * @return mixed
     */
    public static function getAllByGroupId($groupId)
    {
        $data = static::find()
            ->findByGroupId($groupId)
            ->asArray()
            ->all();

        return static::handlerListData($data);
    }

    /**
     * 获取指定用户组id所有菜单id记录
     *
     * @param $groupId
     * @return array
     */
    public static function getAllMenuIdArrByGroupId($groupId)
    {
        $data = static::getAllByGroupId($groupId);
        $menuIdArr = [];
        foreach ($data as $item) {
            if (DpAdminMenu::getByMenuId($item['menu_id'])) {
                $menuIdArr[] = $item['menu_id'];
            }
        }

        return $menuIdArr;
    }

    /**
     * 获取指定用户组id所有记录
     *
     * @param $menuId
     * @return mixed
     */
    public static function getAllByMenuId($menuId)
    {
        $data = static::find()
            ->findByMenuId($menuId)
            ->asArray()
            ->all();

        return static::handlerListData($data);
    }

    /**
     * 删除指定用户组id所有记录
     *
     * @param int $groupId 用户组id
     * @return int
     */
    public static function deleteByGroupId($groupId)
    {
        return static::deleteAll([
            'group_id' => $groupId,
        ]);
    }

    /**
     * 删除指定菜单id所有记录
     *
     * @param int $menuId 菜单id
     * @return int
     */
    public static function deleteByMenuId($menuId)
    {
        return static::deleteAll([
            'menu_id' => $menuId,
        ]);
    }
}
