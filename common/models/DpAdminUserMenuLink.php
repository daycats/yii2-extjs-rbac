<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin_user_menu_link}}".
 *
 * @property integer $user_id
 * @property integer $menu_id
 *
 * @property DpAdminMenu $menu
 * @property DpAdminUser $user
 */
class DpAdminUserMenuLink extends \wsl\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user_menu_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'menu_id'], 'required'],
            [['user_id', 'menu_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户ID',
            'menu_id' => '菜单ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(DpAdminMenu::className(), ['menu_id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(DpAdminUser::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return DpAdminUserMenuLinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminUserMenuLinkQuery(get_called_class());
    }

    /**
     * 获取指定用户ID#对应admin_user表的user_id字段记录
     *
     * @param integer $userId 用户ID#对应admin_user表的user_id字段 
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getByUserId($userId)
    {
        return static::getOneByFind(static::find()->findByUserId($userId));
    }
    
    /**
     * 获取指定菜单ID#对应admin_menu表的menu_id字段记录
     *
     * @param integer $menuId 菜单ID#对应admin_menu表的menu_id字段 
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getByMenuId($menuId)
    {
        return static::getOneByFind(static::find()->findByMenuId($menuId));
    }
    
    /**
     * 获取指定用户ID#对应admin_user表的user_id字段列表记录
     *
     * @param integer $userId 用户ID#对应admin_user表的user_id字段 
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getListByUserId($userId, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByUserId($userId), $order, $page, $pageSize);
    }
    
    /**
     * 获取指定菜单ID#对应admin_menu表的menu_id字段列表记录
     *
     * @param integer $menuId 菜单ID#对应admin_menu表的menu_id字段 
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getListByMenuId($menuId, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByMenuId($menuId), $order, $page, $pageSize);
    }
    
    /**
     * 获取指定用户ID#对应admin_user表的user_id字段所有记录
     *
     * @param integer $userId 用户ID#对应admin_user表的user_id字段 
     * @param string $order|null 排序
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getAllByUserId($userId, $order = null)
    {
        return static::getAllByFind(static::find()->findByUserId($userId), $order);
    }
    
    /**
     * 获取指定菜单ID#对应admin_menu表的menu_id字段所有记录
     *
     * @param integer $menuId 菜单ID#对应admin_menu表的menu_id字段 
     * @param string $order|null 排序
     * @return array|DpAdminUserMenuLink|null
     */
    public static function getAllByMenuId($menuId, $order = null)
    {
        return static::getAllByFind(static::find()->findByMenuId($menuId), $order);
    }

    /**
     * 删除指定用户id所有记录
     *
     * @param int $userId 用户id
     * @return int
     */
    public static function deleteByUserId($userId)
    {
        return static::deleteAll([
            'user_id' => $userId,
        ]);
    }

    /**
     * 获取指定用户的所有菜单id记录
     *
     * @param int $userId 用户id
     * @return array
     */
    public static function getAllMenuIdArrByUserId($userId)
    {
        $data = static::find()
            ->findByUserId($userId)
            ->asArray()
            ->all();

        $menuIdArr = [];
        foreach ($data as $item) {
            $menu = DpAdminMenu::find()
                ->findByMenuId($item['menu_id'])
                ->asArray()
                ->one();
            if ($menu) {
                $menuIdArr[] = $item['menu_id'];
            }
        }

        return $menuIdArr;
    }
}
