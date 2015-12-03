<?php

namespace wsl\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpAdminMenuUrlRule]].
 *
 * @see DpAdminMenuUrlRule
 */
class DpAdminMenuUrlRuleQuery extends ActiveQuery
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
     * 查找指定规则id
     *
     * @param int $ruleId 规则id
     * @return $this
     */
    public function findByRuleId($ruleId)
    {
        $this->andWhere(['rule_id' => $ruleId]);
        return $this;
    }

    /**
     * 查找指定url id
     *
     * @param int $urlId 规则id
     * @return $this
     */
    public function findByUrlId($urlId)
    {
        $this->andWhere(['url_id' => $urlId]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrlRule[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrlRule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}