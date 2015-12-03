<?php

namespace wsl\rbac\models;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dp_admin_menu_url_relation".
 *
 * @property string $link_id
 * @property string $menu_id
 * @property string $url_id
 * @property integer $status
 */
class DpAdminMenuUrlRelation extends ActiveRecord
{
    /**
     * 状态 禁用
     */
    const STATUS_INACTIVE = 0;
    /**
     * 状态 启用
     */
    const STATUS_ACTIVE = 1;
    /**
     * 状态 删除
     */
    const STATUS_DELETE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_admin_menu_url_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'url_id'], 'required'],
            [['menu_id', 'url_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'link_id' => '自增ID',
            'menu_id' => '菜单id',
            'url_id' => 'URL id',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuUrlRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        $find = new DpAdminMenuUrlRelationQuery(get_called_class());
        $find->normal();

        return $find;
    }

    /**
     * 获取菜单url的关联数据
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuUrl()
    {
        return $this->hasOne(DpAdminMenuUrl::className(), ['url_id' => 'url_id']);
    }

    /**
     * 获取关联菜单url数据
     *
     * @return array
     */
    public function getMenuUrlData()
    {
        if (!empty($this['menuUrl'])) {
            return $this['menuUrl']->getAttributes();
        } else {
            return [];
        }
    }

    /**
     * 获取指定菜单id的菜单url一条记录
     *
     * @param int $menuId 菜单url
     * @return array|DpAdminMenuUrlRelation|null
     */
    public static function getUrlByMenuId($menuId)
    {
        $record = static::find()
            ->findByMenuId($menuId)
            ->innerJoinWith('menuUrl')
            ->one();

        $return = [];
        if ($record) {
            $return = $record->getMenuUrlData();
        }

        return $return;
    }

    /**
     * 获取指定菜单id的菜单url所有记录
     *
     * @param int $menuId 菜单url
     * @return array|DpAdminMenuUrlRelation|null
     */
    public static function getAllUrlByMenuId($menuId)
    {
        $records = static::find()
            ->findByMenuId($menuId)
            ->innerJoinWith('menuUrl')
            ->all();

        $return = [];
        if ($records) {
            foreach ($records as $record) {
                $return[] = $record->getMenuUrlData();
            }
        }

        return $return;
    }

    /**
     * 获取指定id数组的url id数组记录
     *
     * @param array $menuIdArr 菜单id数组
     * @return array
     */
    public static function getUrlIdArrByMenuIdArr($menuIdArr)
    {
        $urlIdArr = [];
        foreach ($menuIdArr as $menuId) {
            $link = static::find()
                ->findByMenuId($menuId)
                ->active()
                ->asArray()
                ->all();
            if ($link) {
                foreach ($link as $item) {
                    $urlIdArr[] = $item['url_id'];
                }
            }
        }

        return array_unique($urlIdArr);
    }

    /**
     * 获取列表记录
     *
     * @param array|string $condition 条件
     * @param array $params 参数
     * @param string $order 排序
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array
     */
    public static function getListByCondition($condition = '', $params = [], $order = null, $page = 1, $pageSize = 20)
    {
        $pagination = new Pagination();
        $pagination->setPage($page);
        $pagination->setPageSize($pageSize);
        $pagination->totalCount = static::find()
            ->innerJoinWith('menuUrl')
            ->andWhere($condition, $params)
            ->count(1);
        $list = static::find()
            ->innerJoinWith('menuUrl')
            ->andWhere($condition, $params)
            ->offset($pagination->getOffset() - $pagination->getPageSize())
            ->limit($pagination->getPageSize())
            ->orderBy($order)
            ->asArray()
            ->all();

        return [
            'paginationObj' => $pagination,
            'pagination' => [
                'currentPage' => $page,
                'pageSize' => $pageSize,
                'pageCount' => $pagination->getPageCount(),
                'totalCount' => $pagination->totalCount,
            ],
            'list' => $list,
        ];
    }
}
