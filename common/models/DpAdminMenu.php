<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_menu}}".
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
class DpAdminMenu extends \wsl\db\ActiveRecord
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
        return '{{%admin_menu}}';
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
        return new DpAdminMenuQuery(get_called_class());
    }

    /**
     * 获取指定自增ID记录
     *
     * @param integer $menuId 自增ID
     * @return array|DpAdminMenu|null
     */
    public static function getByMenuId($menuId)
    {
        return static::getOneByFind(static::find()->findByMenuId($menuId));
    }

    /**
     * 获取指定父id,对应本表的menu_id字段记录
     *
     * @param integer $parentId 父id,对应本表的menu_id字段
     * @return array|DpAdminMenu|null
     */
    public static function getByParentId($parentId)
    {
        return static::getOneByFind(static::find()->findByParentId($parentId));
    }

    /**
     * 获取指定菜单名记录
     *
     * @param string $text 菜单名
     * @return array|DpAdminMenu|null
     */
    public static function getByText($text)
    {
        return static::getOneByFind(static::find()->findByText($text));
    }

    /**
     * 获取模糊菜单名记录
     *
     * @param string $text 菜单名
     * @return array|DpAdminMenu|null
     */
    public static function getLikeByText($text)
    {
        return static::getOneByFind(static::find()->findLikeByText($text));
    }

    /**
     * 获取指定标题名记录
     *
     * @param string $title 标题名
     * @return array|DpAdminMenu|null
     */
    public static function getByTitle($title)
    {
        return static::getOneByFind(static::find()->findByTitle($title));
    }

    /**
     * 获取模糊标题名记录
     *
     * @param string $title 标题名
     * @return array|DpAdminMenu|null
     */
    public static function getLikeByTitle($title)
    {
        return static::getOneByFind(static::find()->findLikeByTitle($title));
    }

    /**
     * 获取指定URL记录
     *
     * @param string $url URL
     * @return array|DpAdminMenu|null
     */
    public static function getByUrl($url)
    {
        return static::getOneByFind(static::find()->findByUrl($url));
    }

    /**
     * 获取模糊URL记录
     *
     * @param string $url URL
     * @return array|DpAdminMenu|null
     */
    public static function getLikeByUrl($url)
    {
        return static::getOneByFind(static::find()->findLikeByUrl($url));
    }

    /**
     * 获取指定视图名记录
     *
     * @param string $viewPackage 视图名
     * @return array|DpAdminMenu|null
     */
    public static function getByViewPackage($viewPackage)
    {
        return static::getOneByFind(static::find()->findByViewPackage($viewPackage));
    }

    /**
     * 获取模糊视图名记录
     *
     * @param string $viewPackage 视图名
     * @return array|DpAdminMenu|null
     */
    public static function getLikeByViewPackage($viewPackage)
    {
        return static::getOneByFind(static::find()->findLikeByViewPackage($viewPackage));
    }

    /**
     * 获取指定展开记录
     *
     * @param integer $expanded 展开 0.否,1.是
     * @return array|DpAdminMenu|null
     */
    public static function getByExpanded($expanded)
    {
        return static::getOneByFind(static::find()->findByExpanded($expanded));
    }

    /**
     * 获取指定允许关闭窗口记录
     *
     * @param integer $closable 允许关闭窗口 0.否,1是
     * @return array|DpAdminMenu|null
     */
    public static function getByClosable($closable)
    {
        return static::getOneByFind(static::find()->findByClosable($closable));
    }

    /**
     * 获取指定打开URL记录
     *
     * @param integer $isOpenUrl 打开URL 0.否,1.是
     * @return array|DpAdminMenu|null
     */
    public static function getByIsOpenUrl($isOpenUrl)
    {
        return static::getOneByFind(static::find()->findByIsOpenUrl($isOpenUrl));
    }

    /**
     * 获取指定新的窗口打开记录
     *
     * @param integer $isOpenTarget 新的窗口打开 0.否,1.是
     * @return array|DpAdminMenu|null
     */
    public static function getByIsOpenTarget($isOpenTarget)
    {
        return static::getOneByFind(static::find()->findByIsOpenTarget($isOpenTarget));
    }

    /**
     * 获取指定每次重新打开记录
     *
     * @param integer $isEveryOpen 每次重新打开 0.否,1是
     * @return array|DpAdminMenu|null
     */
    public static function getByIsEveryOpen($isEveryOpen)
    {
        return static::getOneByFind(static::find()->findByIsEveryOpen($isEveryOpen));
    }

    /**
     * 获取指定隐藏记录
     *
     * @param integer $isHide 隐藏 0.否,1.是
     * @return array|DpAdminMenu|null
     */
    public static function getByIsHide($isHide)
    {
        return static::getOneByFind(static::find()->findByIsHide($isHide));
    }

    /**
     * 获取指定显示排序号记录
     *
     * @param integer $displayOrder 显示排序号
     * @return array|DpAdminMenu|null
     */
    public static function getByDisplayOrder($displayOrder)
    {
        return static::getOneByFind(static::find()->findByDisplayOrder($displayOrder));
    }

    /**
     * 获取指定备注记录
     *
     * @param string $note 备注
     * @return array|DpAdminMenu|null
     */
    public static function getByNote($note)
    {
        return static::getOneByFind(static::find()->findByNote($note));
    }

    /**
     * 获取模糊备注记录
     *
     * @param string $note 备注
     * @return array|DpAdminMenu|null
     */
    public static function getLikeByNote($note)
    {
        return static::getOneByFind(static::find()->findLikeByNote($note));
    }

    /**
     * 获取指定状态记录
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @return array|DpAdminMenu|null
     */
    public static function getByStatus($status)
    {
        return static::getOneByFind(static::find()->findByStatus($status));
    }

    /**
     * 获取指定自增ID列表记录
     *
     * @param integer $menuId 自增ID
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByMenuId($menuId, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByMenuId($menuId), $order, $page, $pageSize);
    }

    /**
     * 获取指定父id,对应本表的menu_id字段列表记录
     *
     * @param integer $parentId 父id,对应本表的menu_id字段
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByParentId($parentId, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByParentId($parentId), $order, $page, $pageSize);
    }

    /**
     * 获取指定菜单名列表记录
     *
     * @param string $text 菜单名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByText($text, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByText($text), $order, $page, $pageSize);
    }

    /**
     * 获取模糊菜单名列表记录
     *
     * @param string $text 菜单名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListLikeByText($text, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByText($text), $order, $page, $pageSize);
    }

    /**
     * 获取指定标题名列表记录
     *
     * @param string $title 标题名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByTitle($title, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByTitle($title), $order, $page, $pageSize);
    }

    /**
     * 获取模糊标题名列表记录
     *
     * @param string $title 标题名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListLikeByTitle($title, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByTitle($title), $order, $page, $pageSize);
    }

    /**
     * 获取指定URL列表记录
     *
     * @param string $url URL
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByUrl($url, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByUrl($url), $order, $page, $pageSize);
    }

    /**
     * 获取模糊URL列表记录
     *
     * @param string $url URL
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListLikeByUrl($url, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByUrl($url), $order, $page, $pageSize);
    }

    /**
     * 获取指定视图名列表记录
     *
     * @param string $viewPackage 视图名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByViewPackage($viewPackage, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByViewPackage($viewPackage), $order, $page, $pageSize);
    }

    /**
     * 获取模糊视图名列表记录
     *
     * @param string $viewPackage 视图名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListLikeByViewPackage($viewPackage, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByViewPackage($viewPackage), $order, $page, $pageSize);
    }

    /**
     * 获取指定展开列表记录
     *
     * @param integer $expanded 展开 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByExpanded($expanded, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByExpanded($expanded), $order, $page, $pageSize);
    }

    /**
     * 获取指定允许关闭窗口列表记录
     *
     * @param integer $closable 允许关闭窗口 0.否,1是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByClosable($closable, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByClosable($closable), $order, $page, $pageSize);
    }

    /**
     * 获取指定打开URL列表记录
     *
     * @param integer $isOpenUrl 打开URL 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByIsOpenUrl($isOpenUrl, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsOpenUrl($isOpenUrl), $order, $page, $pageSize);
    }

    /**
     * 获取指定新的窗口打开列表记录
     *
     * @param integer $isOpenTarget 新的窗口打开 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByIsOpenTarget($isOpenTarget, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsOpenTarget($isOpenTarget), $order, $page, $pageSize);
    }

    /**
     * 获取指定每次重新打开列表记录
     *
     * @param integer $isEveryOpen 每次重新打开 0.否,1是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByIsEveryOpen($isEveryOpen, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsEveryOpen($isEveryOpen), $order, $page, $pageSize);
    }

    /**
     * 获取指定隐藏列表记录
     *
     * @param integer $isHide 隐藏 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByIsHide($isHide, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsHide($isHide), $order, $page, $pageSize);
    }

    /**
     * 获取指定显示排序号列表记录
     *
     * @param integer $displayOrder 显示排序号
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByDisplayOrder($displayOrder, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByDisplayOrder($displayOrder), $order, $page, $pageSize);
    }

    /**
     * 获取指定备注列表记录
     *
     * @param string $note 备注
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByNote($note, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByNote($note), $order, $page, $pageSize);
    }

    /**
     * 获取模糊备注列表记录
     *
     * @param string $note 备注
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListLikeByNote($note, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByNote($note), $order, $page, $pageSize);
    }

    /**
     * 获取指定状态列表记录
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminMenu|null
     */
    public static function getListByStatus($status, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByStatus($status), $order, $page, $pageSize);
    }

    /**
     * 获取指定自增ID所有记录
     *
     * @param integer $menuId 自增ID
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByMenuId($menuId, $order = null)
    {
        return static::getAllByFind(static::find()->findByMenuId($menuId), $order);
    }

    /**
     * 获取指定父id,对应本表的menu_id字段所有记录
     *
     * @param integer $parentId 父id,对应本表的menu_id字段
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByParentId($parentId, $order = null)
    {
        return static::getAllByFind(static::find()->findByParentId($parentId), $order);
    }

    /**
     * 获取指定菜单名所有记录
     *
     * @param string $text 菜单名
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByText($text, $order = null)
    {
        return static::getAllByFind(static::find()->findByText($text), $order);
    }

    /**
     * 获取模糊菜单名所有记录
     *
     * @param string $text 菜单名
     * @param string|null $order 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllLikeByText($text, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByText($text), $order);
    }

    /**
     * 获取指定标题名所有记录
     *
     * @param string $title 标题名
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByTitle($title, $order = null)
    {
        return static::getAllByFind(static::find()->findByTitle($title), $order);
    }

    /**
     * 获取模糊标题名所有记录
     *
     * @param string $title 标题名
     * @param string|null $order 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllLikeByTitle($title, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByTitle($title), $order);
    }

    /**
     * 获取指定URL所有记录
     *
     * @param string $url URL
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByUrl($url, $order = null)
    {
        return static::getAllByFind(static::find()->findByUrl($url), $order);
    }

    /**
     * 获取模糊URL所有记录
     *
     * @param string $url URL
     * @param string|null $order 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllLikeByUrl($url, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByUrl($url), $order);
    }

    /**
     * 获取指定视图名所有记录
     *
     * @param string $viewPackage 视图名
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByViewPackage($viewPackage, $order = null)
    {
        return static::getAllByFind(static::find()->findByViewPackage($viewPackage), $order);
    }

    /**
     * 获取模糊视图名所有记录
     *
     * @param string $viewPackage 视图名
     * @param string|null $order 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllLikeByViewPackage($viewPackage, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByViewPackage($viewPackage), $order);
    }

    /**
     * 获取指定展开所有记录
     *
     * @param integer $expanded 展开 0.否,1.是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByExpanded($expanded, $order = null)
    {
        return static::getAllByFind(static::find()->findByExpanded($expanded), $order);
    }

    /**
     * 获取指定允许关闭窗口所有记录
     *
     * @param integer $closable 允许关闭窗口 0.否,1是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByClosable($closable, $order = null)
    {
        return static::getAllByFind(static::find()->findByClosable($closable), $order);
    }

    /**
     * 获取指定打开URL所有记录
     *
     * @param integer $isOpenUrl 打开URL 0.否,1.是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByIsOpenUrl($isOpenUrl, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsOpenUrl($isOpenUrl), $order);
    }

    /**
     * 获取指定新的窗口打开所有记录
     *
     * @param integer $isOpenTarget 新的窗口打开 0.否,1.是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByIsOpenTarget($isOpenTarget, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsOpenTarget($isOpenTarget), $order);
    }

    /**
     * 获取指定每次重新打开所有记录
     *
     * @param integer $isEveryOpen 每次重新打开 0.否,1是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByIsEveryOpen($isEveryOpen, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsEveryOpen($isEveryOpen), $order);
    }

    /**
     * 获取指定隐藏所有记录
     *
     * @param integer $isHide 隐藏 0.否,1.是
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByIsHide($isHide, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsHide($isHide), $order);
    }

    /**
     * 获取指定显示排序号所有记录
     *
     * @param integer $displayOrder 显示排序号
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByDisplayOrder($displayOrder, $order = null)
    {
        return static::getAllByFind(static::find()->findByDisplayOrder($displayOrder), $order);
    }

    /**
     * 获取指定备注所有记录
     *
     * @param string $note 备注
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByNote($note, $order = null)
    {
        return static::getAllByFind(static::find()->findByNote($note), $order);
    }

    /**
     * 获取模糊备注所有记录
     *
     * @param string $note 备注
     * @param string|null $order 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllLikeByNote($note, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByNote($note), $order);
    }

    /**
     * 获取指定状态所有记录
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @param string $order|null 排序
     * @return array|DpAdminMenu|null
     */
    public static function getAllByStatus($status, $order = null)
    {
        return static::getAllByFind(static::find()->findByStatus($status), $order);
    }

}
