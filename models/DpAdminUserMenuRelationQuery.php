<?php

namespace wsl\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpAdminUserMenuRelation]].
 *
 * @see DpAdminUserMenuLink
 */
class DpAdminUserMenuRelationQuery extends ActiveQuery
{
    /*public function inactive()
    {
        $this->andWhere('[[status]]=0');
        return $this;
    }

    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    public function delete()
    {
        $this->andWhere('[[status]]=2');
        return $this;
    }

    public function normal()
    {
        $this->andWhere('[[status]]=0 or [[status]]=1');
        return $this;
    }*/

    /**
     * 查找指定用户ID#对应admin_user表的user_id字段
     *
     * @param integer $userId 用户ID#对应admin_user表的user_id字段 
     * @return $this
     */
    public function findByUserId($userId)
    {
        $this->andWhere(['user_id' => $userId]);
        return $this;
    }
    
    /**
     * 查找指定菜单ID#对应admin_menu表的menu_id字段
     *
     * @param integer $menuId 菜单ID#对应admin_menu表的menu_id字段 
     * @return $this
     */
    public function findByMenuId($menuId)
    {
        $this->andWhere(['menu_id' => $menuId]);
        return $this;
    }
    
    /**
     * @inheritdoc
     * @return DpAdminUserMenuRelation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminUserMenuRelation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}