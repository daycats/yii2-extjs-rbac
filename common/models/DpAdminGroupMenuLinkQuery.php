<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[DpAdminGroupMenuLink]].
 *
 * @see DpAdminGroupMenuLink
 */
class DpAdminGroupMenuLinkQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * 查找指定用户组id
     *
     * @param int $groupId 用户组id
     * @return $this
     */
    public function findByGroupId($groupId)
    {
        $this->andWhere([
            'group_id' => $groupId,
        ]);
        return $this;
    }

    /**
     * 查找指定菜单id
     *
     * @param int $menuId 菜单id
     * @return $this
     */
    public function findByMenuId($menuId)
    {
        $this->andWhere([
            'menu_id' => $menuId,
        ]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return DpAdminGroupMenuLink[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminGroupMenuLink|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}