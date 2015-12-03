<?php

namespace wsl\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[DpAdminUser]].
 *
 * @see DpAdminUser
 */
class DpAdminUserQuery extends ActiveQuery
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

    public function secure()
    {
        $this->andWhere('[[user_id]]!=1');
        return $this;
    }
    /**
     * 查找指定自增ID号
     *
     * @param integer $userId 自增ID号
     * @return $this
     */
    public function findByUserId($userId)
    {
        $this->andWhere(['user_id' => $userId]);
        return $this;
    }

    /**
     * 查找指定用户名
     *
     * @param string $username 用户名
     * @return $this
     */
    public function findByUsername($username)
    {
        $this->andWhere(['username' => $username]);
        return $this;
    }

    /**
     * 查找模糊用户名
     *
     * @param string $username 用户名
     * @return $this
     */
    public function findLikeByUsername($username)
    {
        $this->andWhere('username like :username', [
            ':username' => '%' . $username . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定昵称
     *
     * @param string $nickname 昵称
     * @return $this
     */
    public function findByNickname($nickname)
    {
        $this->andWhere(['nickname' => $nickname]);
        return $this;
    }

    /**
     * 查找模糊昵称
     *
     * @param string $nickname 昵称
     * @return $this
     */
    public function findLikeByNickname($nickname)
    {
        $this->andWhere('nickname like :nickname', [
            ':nickname' => '%' . $nickname . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定哈希密码
     *
     * @param string $passwordHash 哈希密码
     * @return $this
     */
    public function findByPasswordHash($passwordHash)
    {
        $this->andWhere(['password_hash' => $passwordHash]);
        return $this;
    }

    /**
     * 查找模糊哈希密码
     *
     * @param string $passwordHash 哈希密码
     * @return $this
     */
    public function findLikeByPasswordHash($passwordHash)
    {
        $this->andWhere('password_hash like :password_hash', [
            ':password_hash' => '%' . $passwordHash . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定访问令牌
     *
     * @param string $passwordResetToken 访问令牌
     * @return $this
     */
    public function findByPasswordResetToken($passwordResetToken)
    {
        $this->andWhere(['password_reset_token' => $passwordResetToken]);
        return $this;
    }

    /**
     * 查找模糊访问令牌
     *
     * @param string $passwordResetToken 访问令牌
     * @return $this
     */
    public function findLikeByPasswordResetToken($passwordResetToken)
    {
        $this->andWhere('password_reset_token like :password_reset_token', [
            ':password_reset_token' => '%' . $passwordResetToken . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定验证码
     *
     * @param string $authKey 验证码
     * @return $this
     */
    public function findByAuthKey($authKey)
    {
        $this->andWhere(['auth_key' => $authKey]);
        return $this;
    }

    /**
     * 查找模糊验证码
     *
     * @param string $authKey 验证码
     * @return $this
     */
    public function findLikeByAuthKey($authKey)
    {
        $this->andWhere('auth_key like :auth_key', [
            ':auth_key' => '%' . $authKey . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定用户组id集,对应admin_group的id字段
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @return $this
     */
    public function findByGroupIds($groupIds)
    {
        $this->andWhere(['group_ids' => $groupIds]);
        return $this;
    }

    /**
     * 查找模糊用户组id集,对应admin_group的id字段
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @return $this
     */
    public function findLikeByGroupIds($groupIds)
    {
        $this->andWhere('group_ids like :group_ids', [
            ':group_ids' => '%' . $groupIds . '%',
        ]);
        return $this;
    }

    /**
     * 查找指定用户组权限
     *
     * @param integer $isGroupAccess 用户组权限 0.关闭,1.开启
     * @return $this
     */
    public function findByIsGroupAccess($isGroupAccess)
    {
        $this->andWhere(['is_group_access' => $isGroupAccess]);
        return $this;
    }

    /**
     * 查找指定用户权限
     *
     * @param integer $isUserAccess 用户权限 0.关闭,1.开启
     * @return $this
     */
    public function findByIsUserAccess($isUserAccess)
    {
        $this->andWhere(['is_user_access' => $isUserAccess]);
        return $this;
    }

    /**
     * 查找指定系统用户
     *
     * @param integer $isSystem 系统用户 0.否,1.是
     * @return $this
     */
    public function findByIsSystem($isSystem)
    {
        $this->andWhere(['is_system' => $isSystem]);
        return $this;
    }

    /**
     * 查找指定超级用户
     *
     * @param integer $isSuper 超级用户 0.否,1.是
     * @return $this
     */
    public function findByIsSuper($isSuper)
    {
        $this->andWhere(['is_super' => $isSuper]);
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
     * @return DpAdminUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DpAdminUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}