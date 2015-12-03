<?php

namespace wsl\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpAdminMenuUrl]].
 *
 * @see DpAdminMenuUrl
 */
class DpAdminMenuUrlQuery extends ActiveQuery
{
    /**
     * @var string 表名
     */
    public $tableName = '';

    public function init()
    {
        /**
         * @var $modelClass DpAdminMenuUrlRelation
         */
        $modelClass = $this->modelClass;
        $this->tableName = $modelClass::tableName();
        parent::init();
    }

    public function active()
    {
        $this->andWhere($this->tableName . '.status=1');
        return $this;
    }

    public function normal()
    {
        $this->andWhere($this->tableName . '.status=0 or ' . $this->tableName . '.status=1');
        return $this;
    }

    /**
     * 查找指定url id
     *
     * @param int $urlId url id
     * @return $this
     */
    public function findByUrlId($urlId)
    {
        $this->andWhere(['url_id' => $urlId]);
        return $this;
    }

    /**
     * 查找指定路由
     *
     * @param string $route 路由
     * @return $this
     */
    public function findByRoute($route)
    {
        $this->andWhere([
            'route' => $route,
        ]);
        return $this;
    }

    /**
     * 查找指定别名
     *
     * @param string $alias 别名
     * @return $this
     */
    public function findByAlias($alias)
    {
        $this->andWhere([
            'alias' => $alias,
        ]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrl[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrl|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}