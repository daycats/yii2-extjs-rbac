<?php

namespace wsl\rbac\models;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dp_admin_group".
 *
 * @property integer $group_id
 * @property string $name
 * @property string $is_system
 * @property string $note
 * @property integer $status
 */
class DpAdminGroup extends ActiveRecord
{
    /**
     * 状态 禁用
     */
    const STATUS_DISABLED = 0;
    /**
     * 状态 启用
     */
    const STATUS_ENABLED = 1;
    /**
     * 状态 删除
     */
    const STATUS_DELETE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_admin_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_system', 'status'], 'integer'],
            [['note'], 'default', 'value' => ''],
            [['name', 'note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => '自增ID',
            'name' => '名称',
            'is_system' => '系统用户组',
            'note' => '备注',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return DpAdminGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        $find = new DpAdminGroupQuery(get_called_class());
        $find->normal();

        return $find;
    }

    /**
     * 获取指定用户组id
     *
     * @param int $groupId 用户组id
     * @return array|DpAdminGroup|null
     */
    public static function getByGroupId($groupId)
    {
        $data = static::find()
            ->findByGroupId($groupId)
            ->asArray()
            ->one();

        return $data;
    }

    /**
     * 获取指定分组id数组的菜单id数组
     *
     * @param array $groupIdArr 分组id数组
     * @return array
     */
    public static function getMenuIdArrByGroupIdArr($groupIdArr)
    {
        $menuIdArr = [];
        foreach ($groupIdArr as $groupId) {
            if (static::getByGroupId($groupId)) {
                $menuIdArr = array_merge($menuIdArr, DpAdminGroupMenuRelation::getAllMenuIdArrByGroupId($groupId));
            }
        }

        return array_unique($menuIdArr);
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
    public static function getListByCondition($condition = '', $params = [], $order = 'group_id desc', $page = 1, $pageSize = 20)
    {
        $pagination = new Pagination();
        $pagination->setPage($page);
        $pagination->setPageSize($pageSize);
        $pagination->totalCount = static::find()->andWhere($condition, $params)->count(1);
        $list = static::find()
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

    /**
     * 获取所有记录
     *
     * @param string $order 排序字段
     * @return array|\wsl\rbac\models\DpAdminGroup[]
     */
    public static function getAll($order = 'group_id asc')
    {
        $list = static::find()
            ->orderBy($order)
            ->asArray()
            ->all();

        return $list;
    }
}
