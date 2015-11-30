<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[DpAdminMenu]].
 *
 * @see DpAdminMenu
 */
class DpAdminMenuQuery extends \wsl\db\ActiveQuery
{
    public function inactive()
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
    }

    /**
     * 查找指定自增ID
     *
     * @param integer $menuId 自增ID
     * @return $this
     */
    public function findByMenuId($menuId)
    {
        $this->andWhere(['menu_id' => $menuId]);
        return $this;
    }

    /**
     * 查找指定父id,对应本表的menu_id字段
     *
     * @param integer $parentId 父id,对应本表的menu_id字段
     * @return $this
     */
    public function findByParentId($parentId)
    {
        $this->andWhere(['parent_id' => $parentId]);
        return $this;
    }

    /**
     * 查找指定菜单名
     *
     * @param string $text 菜单名
     * @return $this
     */
    public function findByText($text)
    {
        $this->andWhere(['text' => $text]);
        return $this;
    }

    /**
     * 查找模糊菜单名
     *
     * @param string $text 菜单名
     * @return $this
     */
    public function findLikeByText($text)
    {
        $this->andWhere('text like :text', [
            ':text' => '%' . $text . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定标题名
     *
     * @param string $title 标题名
     * @return $this
     */
    public function findByTitle($title)
    {
        $this->andWhere(['title' => $title]);
        return $this;
    }

    /**
     * 查找模糊标题名
     *
     * @param string $title 标题名
     * @return $this
     */
    public function findLikeByTitle($title)
    {
        $this->andWhere('title like :title', [
            ':title' => '%' . $title . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定URL
     *
     * @param string $url URL
     * @return $this
     */
    public function findByUrl($url)
    {
        $this->andWhere(['url' => $url]);
        return $this;
    }

    /**
     * 查找模糊URL
     *
     * @param string $url URL
     * @return $this
     */
    public function findLikeByUrl($url)
    {
        $this->andWhere('url like :url', [
            ':url' => '%' . $url . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定视图名
     *
     * @param string $viewPackage 视图名
     * @return $this
     */
    public function findByViewPackage($viewPackage)
    {
        $this->andWhere(['view_package' => $viewPackage]);
        return $this;
    }

    /**
     * 查找模糊视图名
     *
     * @param string $viewPackage 视图名
     * @return $this
     */
    public function findLikeByViewPackage($viewPackage)
    {
        $this->andWhere('view_package like :view_package', [
            ':view_package' => '%' . $viewPackage . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定展开
     *
     * @param integer $expanded 展开 0.否,1.是
     * @return $this
     */
    public function findByExpanded($expanded)
    {
        $this->andWhere(['expanded' => $expanded]);
        return $this;
    }

    /**
     * 查找指定允许关闭窗口
     *
     * @param integer $closable 允许关闭窗口 0.否,1是
     * @return $this
     */
    public function findByClosable($closable)
    {
        $this->andWhere(['closable' => $closable]);
        return $this;
    }

    /**
     * 查找指定文件夹
     *
     * @param integer $isFolder 文件夹
     * @return $this
     */
    public function findByIsFolder($isFolder)
    {
        $this->andWhere(['is_folder' => $isFolder]);
        return $this;
    }

    /**
     * 查找指定打开URL
     *
     * @param integer $isOpenUrl 打开URL 0.否,1.是
     * @return $this
     */
    public function findByIsOpenUrl($isOpenUrl)
    {
        $this->andWhere(['is_open_url' => $isOpenUrl]);
        return $this;
    }

    /**
     * 查找指定新的窗口打开
     *
     * @param integer $isOpenTarget 新的窗口打开 0.否,1.是
     * @return $this
     */
    public function findByIsOpenTarget($isOpenTarget)
    {
        $this->andWhere(['is_open_target' => $isOpenTarget]);
        return $this;
    }

    /**
     * 查找指定每次重新打开
     *
     * @param integer $isEveryOpen 每次重新打开 0.否,1是
     * @return $this
     */
    public function findByIsEveryOpen($isEveryOpen)
    {
        $this->andWhere(['is_every_open' => $isEveryOpen]);
        return $this;
    }

    /**
     * 查找指定隐藏
     *
     * @param integer $isHide 隐藏 0.否,1.是
     * @return $this
     */
    public function findByIsHide($isHide)
    {
        $this->andWhere(['is_hide' => $isHide]);
        return $this;
    }

    /**
     * 查找指定显示排序号
     *
     * @param integer $displayOrder 显示排序号
     * @return $this
     */
    public function findByDisplayOrder($displayOrder)
    {
        $this->andWhere(['display_order' => $displayOrder]);
        return $this;
    }

    /**
     * 查找指定备注
     *
     * @param string $note 备注
     * @return $this
     */
    public function findByNote($note)
    {
        $this->andWhere(['note' => $note]);
        return $this;
    }

    /**
     * 查找模糊备注
     *
     * @param string $note 备注
     * @return $this
     */
    public function findLikeByNote($note)
    {
        $this->andWhere('note like :note', [
            ':note' => '%' . $note . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定状态
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @return $this
     */
    public function findByStatus($status)
    {
        $this->andWhere(['status' => $status]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return DpAdminMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}