<?php

namespace wsl\rbac\models;


use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpConfig]].
 *
 * @see DpConfig
 */
class DpConfigQuery extends ActiveQuery
{
    public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }

    /**
     * 查找指定名称
     *
     * @param string $name 名称
     * @return $this
     */
    public function findByName($name)
    {
        $this->andWhere([
            'name' => $name,
        ]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return DpConfig[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpConfig|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}