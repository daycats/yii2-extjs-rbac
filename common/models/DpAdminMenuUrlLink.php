<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_menu_url_link}}".
 *
 * @property string $link_id
 * @property string $menu_id
 * @property string $url_id
 * @property integer $status
 */
class DpAdminMenuUrlLink extends \wsl\db\ActiveRecord
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
        return '{{%admin_menu_url_link}}';
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
     * @return DpAdminMenuUrlLinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminMenuUrlLinkQuery(get_called_class());
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
     * @return array|DpAdminMenuUrlLink|null
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
     * @return array|DpAdminMenuUrlLink|null
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
}
