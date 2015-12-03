<?php

namespace wsl\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpAdminGroup]].
 *
 * @see DpAdminGroup
 */
class DpAdminGroupQuery extends ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    public function normal()
    {
        $this->andWhere('[[status]]=0 or [[status]]=1');
        return $this;
    }

    /**
     * 查找是否为系统用户组
     *
     * @param int $isSystem 是否为系统用户组
     * @return $this
     */
    public function findByIsSystem($isSystem)
    {
        $this->andWhere([
            'is_system' => $isSystem,
        ]);
        return $this;
    }

    /**
     * 查找指定分组id
     *
     * @param int $groupId 分组id
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
     * @inheritdoc
     * @return DpAdminGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}