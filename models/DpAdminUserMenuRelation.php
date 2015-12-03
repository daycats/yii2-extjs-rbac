<?php

namespace wsl\rbac\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dp_admin_user_menu_relation".
 *
 * @property integer $user_id
 * @property integer $menu_id
 *
 * @property DpAdminMenu $menu
 * @property DpAdminUser $user
 */
class DpAdminUserMenuRelation extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_admin_user_menu_relation';
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
     * @return DpAdminUserMenuRelationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpAdminUserMenuRelationQuery(get_called_class());
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
