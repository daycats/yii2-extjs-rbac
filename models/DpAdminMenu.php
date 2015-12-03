<?php

namespace wsl\rbac\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "dp_admin_menu".
 *
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string $text
 * @property string $title
 * @property string $url
 * @property string $view_package
 * @property integer $expanded
 * @property integer $closable
 * @property integer $is_folder
 * @property integer $is_open_url
 * @property integer $is_open_target
 * @property integer $is_every_open
 * @property integer $is_hide
 * @property integer $display_order
 * @property string $note
 * @property integer $status
 */
class DpAdminMenu extends ActiveRecord
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
    const STATUS_DELETE = 2;
    /**
     * @see DpAdminMenu::$expanded 对应名称
     */
    const EXPANDED = [
        '0' => '否',
        '1' => '是',
    ];
    /**
     * @see DpAdminMenu::$closable 对应名称
     */
    const CLOSABLE = [
        '0' => '否',
        '1是' => '',
    ];
    /**
     * @see DpAdminMenu::$status 对应名称
     */
    const STATUS = [
        '0' => '禁用',
        '1' => '启用',
        '2' => '删除',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'expanded', 'closable', 'is_folder',  'is_open_url', 'is_open_target', 'is_every_open', 'is_hide', 'display_order', 'status'], 'integer'],
            [['text'], 'required'],
            [['title', 'url', 'view_package', 'note'], 'default', 'value' => ''],
            [['text', 'title', 'url', 'view_package'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => '自增ID',
            'parent_id' => '父id',
            'text' => '菜单名',
            'title' => '标题名',
            'url' => 'URL',
            'view_package' => '视图名',
            'expanded' => '展开',
            'closable' => '允许关闭窗口',
            'is_folder' => '文件夹',
            'is_open_url' => '打开URL',
            'is_open_target' => '新的窗口打开',
            'is_every_open' => '每次重新打开',
            'is_hide' => '隐藏',
            'display_order' => '显示排序号',
            'note' => '备注',
            'status' => '状态',
        ];
    }

    public function beforeSave($insert)
    {
        if (!is_numeric($this->display_order)) {
            $find = static::find()->findByParentId($this->parent_id);
            if ($this->menu_id) {
                $find->andWhere('menu_id!=:menu_id', [
                    ':menu_id' => $this->menu_id
                ]);
            }
            $this->display_order = $find->max('display_order') + 1;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @return DpAdminMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        $find = new DpAdminMenuQuery(get_called_class());
        $find->normal();

        return $find;
    }

    /**
     * 获取指定id记录
     *
     * @param int $menuId 菜单id
     * @return array|DpAdminMenu|null
     */
    public static function getByMenuId($menuId)
    {
        return static::find()
            ->findByMenuId($menuId)
            ->asArray()
            ->one();
    }

    /**
     * 根据parent_id获取tree数据
     *
     * @param int $parentId 父级id
     * @param array|string $condition 条件
     * @param array $params 条件参数
     * @param string $order 排序
     * @return array
     */
    public static function getTreeByParentId($parentId, $condition = '', $params = [], $order = 'display_order asc')
    {
        $data = static::find()
            ->normal()
            ->findByParentId($parentId)
            ->andWhere($condition, $params)
            ->orderBy($order)
            ->asArray()
            ->all();
        $tree = [];
        if ($data) {
            foreach ($data as $item) {
                $node = [
                    'menu_id' => $item['menu_id'],
                    'parent_id' => $item['parent_id'],
                    'tab_id' => 'tab_' . $item['menu_id'],
                    'text' => $item['text'],
                    'origin_text' => $item['text'],
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'view_package' => $item['view_package'],
                    'expanded' => boolval($item['expanded']),
                    'closable' => boolval($item['closable']),
                    'is_folder' => boolval($item['is_folder']),
                    'is_expand' => boolval($item['expanded']),
                    'is_open_url' => boolval($item['is_open_url']),
                    'is_open_target' => boolval($item['is_open_target']),
                    'is_every_open' => boolval($item['is_every_open']),
                    'is_hide' => boolval($item['is_hide']),
                    'display_order' => $item['display_order'],
                    'note' => $item['note'],
                    'status' => intval($item['status']),
                ];
                $children = static::getTreeByParentId($item['menu_id'], $condition, $params, $order);
                if ($children) {
                    $node['leaf'] = false;
                    $node['children'] = $children;
                } else {
                    $node['leaf'] = true;
                }
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * 获取根据parent_id和菜单id权限数据
     *
     * @param boolean $isSuper 是否为超级用户
     * @param array $menuIdList 菜单id列表
     * @param int $parentId 父级id
     * @param array|string $condition 条件
     * @param array $params 条件参数
     * @param string $order 排序
     * @return array
     */
    public static function getAccessTreeByParentId($isSuper, $menuIdList, $parentId, $condition = '', $params = [], $order = 'display_order asc')
    {
        $data = static::find()
            ->normal()
            ->findByParentId($parentId)
            ->andWhere($condition, $params)
            ->active()
            ->orderBy($order)
            ->asArray()
            ->all();
        $tree = [];
        if ($data) {
            foreach ($data as $item) {
                if ($isSuper || in_array($item['menu_id'], $menuIdList)) {
                    $node = [
                        'menu_id' => $item['menu_id'],
                        'parent_id' => $item['parent_id'],
                        'tab_id' => 'tab_' . $item['menu_id'],
                        'text' => $item['text'],
                        'origin_text' => $item['text'],
                        'title' => $item['title'],
                        'url' => $item['url'],
                        'view_package' => $item['view_package'],
                        'expanded' => boolval($item['expanded']),
                        'is_expand' => boolval($item['expanded']),
                        'closable' => boolval($item['closable']),
                        'is_open_url' => boolval($item['is_open_url']),
                        'is_open_target' => boolval($item['is_open_target']),
                        'is_every_open' => boolval($item['is_every_open']),
                        'is_hide' => boolval($item['is_hide']),
                        'display_order' => $item['display_order'],
                        'note' => $item['note'],
                        'status' => intval($item['status']),
                    ];
                    $children = static::getAccessTreeByParentId($isSuper, $menuIdList, $item['menu_id'], $condition, $params, $order);
                    if ($children) {
                        $node['leaf'] = false;
                        $node['children'] = $children;
                    } else {
                        $node['leaf'] = true;
                    }
                    $tree[] = $node;
                }
            }
        }

        return $tree;
    }

    /**
     * 获取根据parent_id和菜单id权限url数据
     *
     * @param boolean $isSuper 是否为超级用户
     * @param array $menuIdList 菜单id列表
     * @param int $parentId 父级id
     * @param array|string $condition 条件
     * @param array $params 条件参数
     * @param string $order 排序
     * @return array
     */
    public static function getUrlsByParentId($isSuper, $menuIdList, $parentId, $condition = '', $params = [], $order = 'display_order asc')
    {
        $data = static::find()
            ->normal()
            ->findByParentId($parentId)
            ->andWhere($condition, $params)
            ->active()
            ->orderBy($order)
            ->asArray()
            ->all();
        $urls = [];
        if ($data) {
            foreach ($data as $item) {
                if ($isSuper || in_array($item['menu_id'], $menuIdList)) {
                    $menuUrls = DpAdminMenuUrlRelation::getAllUrlByMenuId($item['menu_id']);
                    if ($menuUrls) {
                        foreach ($menuUrls as $menuUrl) {
                            $urls[$menuUrl['alias']] = Url::toRoute($menuUrl['route']);
                        }
                    }
                    $childrenUrls = static::getUrlsByParentId($isSuper, $menuIdList, $item['menu_id'], $condition, $params, $order);
                    if ($childrenUrls) {
                        $urls = array_merge($urls, $childrenUrls);
                    }
                }
            }
        }

        return $urls;
    }

    /**
     * 根据parent_id获取用户组tree数据
     *
     * @param int $groupId 用户组id
     * @param int $parentId 父级id
     * @param string $order 排序
     * @return array
     */
    public static function getGroupMenuTreeByParentId($groupId = 0, $parentId, $order = 'display_order asc')
    {
        $data = static::find()
            ->normal()
            ->findByParentId($parentId)
            ->active()
            ->orderBy($order)
            ->asArray()
            ->all();
        $tree = [];
        if ($data) {
            foreach ($data as $item) {
                $text = $item['text'];
                if ($item['is_hide']) {
                    $text .= ' -- [菜单中隐藏]';
                }
                $node = [
                    'menu_id' => $item['menu_id'],
                    'parent_id' => $item['parent_id'],
                    'tab_id' => 'tab_' . $item['menu_id'],
                    'text' => $text,
                    'origin_text' => $text,
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'view_package' => $item['view_package'],
                    'expanded' => boolval($item['expanded']),
                    'is_expand' => boolval($item['expanded']),
                    'closable' => boolval($item['closable']),
                    'is_open_url' => boolval($item['is_open_url']),
                    'is_open_target' => boolval($item['is_open_target']),
                    'is_every_open' => boolval($item['is_every_open']),
                    'is_hide' => boolval($item['is_hide']),
                    'display_order' => $item['display_order'],
                    'note' => $item['note'],
                    'status' => intval($item['status']),
                    'checked' => DpAdminGroupMenuRelation::find()
                        ->findByGroupId($groupId)
                        ->findByMenuId($item['menu_id'])
                        ->exists()
                ];
                $children = static::getGroupMenuTreeByParentId($groupId, $item['menu_id'], $order);
                if ($children) {
                    $node['leaf'] = false;
                    $node['children'] = $children;
                } else {
                    $node['leaf'] = true;
                }
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * 根据parent_id获取用户tree数据
     *
     * @param int $userId 用户id
     * @param int $parentId 父级id
     * @param string $order 排序
     * @return array
     */
    public static function getUserMenuTreeByParentId($userId = 0, $parentId, $order = 'display_order asc')
    {
        $data = static::find()
            ->normal()
            ->findByParentId($parentId)
            ->active()
            ->orderBy($order)
            ->asArray()
            ->all();
        $tree = [];
        if ($data) {
            foreach ($data as $item) {
                $text = $item['text'];
                if ($item['is_hide']) {
                    $text .= ' -- [菜单中隐藏]';
                }
                $node = [
                    'menu_id' => $item['menu_id'],
                    'parent_id' => $item['parent_id'],
                    'tab_id' => 'tab_' . $item['menu_id'],
                    'text' => $text,
                    'origin_text' => $text,
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'view_package' => $item['view_package'],
                    'expanded' => boolval($item['expanded']),
                    'is_expand' => boolval($item['expanded']),
                    'closable' => boolval($item['closable']),
                    'is_open_url' => boolval($item['is_open_url']),
                    'is_open_target' => boolval($item['is_open_target']),
                    'is_every_open' => boolval($item['is_every_open']),
                    'is_hide' => boolval($item['is_hide']),
                    'display_order' => $item['display_order'],
                    'note' => $item['note'],
                    'status' => intval($item['status']),
                    'checked' => DpAdminUserMenuRelation::find()
                        ->findByUserId($userId)
                        ->findByMenuId($item['menu_id'])
                        ->exists()
                ];
                $children = static::getUserMenuTreeByParentId($userId, $item['menu_id'], $order);
                if ($children) {
                    $node['leaf'] = false;
                    $node['children'] = $children;
                } else {
                    $node['leaf'] = true;
                }
                $tree[] = $node;
            }
        }

        return $tree;
    }
}
