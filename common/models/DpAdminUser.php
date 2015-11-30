<?php

namespace common\models;

use backend\models\DpAdminGroup;
use Yii;
use yii\base\NotSupportedException;
use yii\data\Pagination;
use yii\helpers\StringHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $nickname
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $group_ids
 * @property integer $is_group_access
 * @property integer $is_user_access
 * @property integer $is_system
 * @property integer $is_super
 * @property string $note
 * @property integer $status
 *
 * @property DpAdminUserMenuLink[] $dpAdminUserMenuLinks
 * @property DpAdminMenu[] $menus
 */
class DpAdminUser extends \wsl\db\ActiveRecord implements IdentityInterface
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
     * @see DpAdminUser::$status 对应名称
     */
    const STATUS = [
        '0' => '禁用',
        '1' => '启用',
        '2' => '删除',
    ];

    /**
     * @var string 原始密码
     */
    public $source_password = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'nickname', 'password_hash'], 'required'],
            [['is_group_access', 'is_user_access', 'is_system', 'is_super', 'status'], 'integer'],
            [['username', 'nickname'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'group_ids', 'note'], 'string', 'max' => 255],
            [['source_password', 'auth_key'], 'string', 'max' => 32],
            [['password_reset_token', 'auth_key'], 'default', 'value' => ''],
            [['is_group_access', 'is_user_access', 'is_system', 'is_super'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '自增ID号',
            'username' => '用户名',
            'nickname' => '昵称',
            'password_hash' => '哈希密码',
            'password_reset_token' => '访问令牌',
            'auth_key' => '验证码',
            'group_ids' => '用户组id集',
            'is_group_access' => '启用用户组权限',
            'is_user_access' => '启用用户权限',
            'is_system' => '系统用户',
            'is_super' => '超级用户',
            'note' => '备注',
            'status' => '状态',
        ];
    }

    public function beforeValidate()
    {
        if ($this->source_password) {
            $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash(trim($this->source_password), 4);
        }
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDpAdminUserMenuLinks()
    {
        return $this->hasMany(DpAdminUserMenuLink::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(DpAdminMenu::className(), ['menu_id' => 'menu_id'])->viaTable('{{%admin_user_menu_link}}', ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return DpAdminUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        $find = new DpAdminUserQuery(get_called_class());
        return $find;
    }

    /**
     * 获取用户组id数组
     *
     * @return array
     */
    public function getGroupIdArr()
    {
        $return = [];
        if ($this->group_ids) {
            $return = array_filter(StringHelper::explode($this->group_ids, ',', true, true), function ($groupId) {
                return is_numeric($groupId) && DpAdminGroup::find()->findByGroupId($groupId)->exists();
            });
        }

        return $return;
    }

    /**
     * 获取用户组名称
     *
     * @return array
     */
    public function getGroupNames()
    {
        $names = [];
        $groupIdArr = $this->getGroupIdArr();
        foreach ($groupIdArr as $groupId) {
            $group = DpAdminGroup::getByGroupId($groupId);
            if ($group) {
                $names[] = $group['name'];
            }
        }

        return $names;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 获取指定用户id记录
     *
     * @param int $userId 编号
     * @return array|DpAdminUser|null
     */
    public static function getByUserId($userId)
    {
        return static::find()
            ->andWhere(['user_id' => $userId])
            ->asArray()
            ->one();
    }

    /**
     * 添加用户
     *
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $password 密码
     * @param string $note 备注
     * @param int $status 状态
     * @return mixed
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function addUser($username, $nickname, $password, $note = '', $status = 1)
    {
        $obj = new static();
        $obj->setAttributes([
            'username' => $username,
            'nickname' => $nickname,
            'password' => \Yii::$app->getSecurity()->generatePasswordHash($password, 4),
            'note' => $note,
            'status' => $status,
        ]);

        return $obj->save();
    }

    /**
     * 获取列表记录
     *
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array
     */
    public static function getList($page = 1, $pageSize = 20)
    {
        $pagination = new Pagination();
        $pagination->setPage($page);
        $pagination->setPageSize($pageSize);
        $pagination->totalCount = static::find()->andWhere($condition, $params)->count(1);
        $list = static::find()
            ->offset($pagination->getOffset() - $pagination->getPageSize())
            ->limit($pagination->getPageSize())
            ->orderBy('user_id desc')
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
     * 获取列表记录
     *
     * @param array|string $condition 条件
     * @param array $params 参数
     * @param string $order 排序
     * @param int $page 页码
     * @param int $pageSize 每页数量
     * @return array
     */
    public static function getListByCondition($condition = '', $params = [], $order = 'user_id desc', $page = 1, $pageSize = 20)
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
     * 获取指定用户名记录
     *
     * @param string $username 用户名
     * @return array|DpAdminUser|null
     */
    public static function getByUsername($username)
    {
        return static::find()
            ->findByUsername($username)
            ->asArray()
            ->one();
    }


    /**
     * 获取模糊用户名记录
     *
     * @param string $username 用户名
     * @return array|DpAdminUser|null
     */
    public static function getLikeByUsername($username)
    {
        return static::getOneByFind(static::find()->findLikeByUsername($username));
    }

    /**
     * 获取指定昵称记录
     *
     * @param string $nickname 昵称
     * @return array|DpAdminUser|null
     */
    public static function getByNickname($nickname)
    {
        return static::getOneByFind(static::find()->findByNickname($nickname));
    }

    /**
     * 获取模糊昵称记录
     *
     * @param string $nickname 昵称
     * @return array|DpAdminUser|null
     */
    public static function getLikeByNickname($nickname)
    {
        return static::getOneByFind(static::find()->findLikeByNickname($nickname));
    }

    /**
     * 获取指定哈希密码记录
     *
     * @param string $passwordHash 哈希密码
     * @return array|DpAdminUser|null
     */
    public static function getByPasswordHash($passwordHash)
    {
        return static::getOneByFind(static::find()->findByPasswordHash($passwordHash));
    }

    /**
     * 获取模糊哈希密码记录
     *
     * @param string $passwordHash 哈希密码
     * @return array|DpAdminUser|null
     */
    public static function getLikeByPasswordHash($passwordHash)
    {
        return static::getOneByFind(static::find()->findLikeByPasswordHash($passwordHash));
    }

    /**
     * 获取指定访问令牌记录
     *
     * @param string $passwordResetToken 访问令牌
     * @return array|DpAdminUser|null
     */
    public static function getByPasswordResetToken($passwordResetToken)
    {
        return static::getOneByFind(static::find()->findByPasswordResetToken($passwordResetToken));
    }

    /**
     * 获取模糊访问令牌记录
     *
     * @param string $passwordResetToken 访问令牌
     * @return array|DpAdminUser|null
     */
    public static function getLikeByPasswordResetToken($passwordResetToken)
    {
        return static::getOneByFind(static::find()->findLikeByPasswordResetToken($passwordResetToken));
    }

    /**
     * 获取指定验证码记录
     *
     * @param string $authKey 验证码
     * @return array|DpAdminUser|null
     */
    public static function getByAuthKey($authKey)
    {
        return static::getOneByFind(static::find()->findByAuthKey($authKey));
    }

    /**
     * 获取模糊验证码记录
     *
     * @param string $authKey 验证码
     * @return array|DpAdminUser|null
     */
    public static function getLikeByAuthKey($authKey)
    {
        return static::getOneByFind(static::find()->findLikeByAuthKey($authKey));
    }

    /**
     * 获取指定用户组id集,对应admin_group的id字段记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @return array|DpAdminUser|null
     */
    public static function getByGroupIds($groupIds)
    {
        return static::getOneByFind(static::find()->findByGroupIds($groupIds));
    }

    /**
     * 获取模糊用户组id集,对应admin_group的id字段记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @return array|DpAdminUser|null
     */
    public static function getLikeByGroupIds($groupIds)
    {
        return static::getOneByFind(static::find()->findLikeByGroupIds($groupIds));
    }

    /**
     * 获取指定用户组权限记录
     *
     * @param integer $isGroupAccess 用户组权限 0.关闭,1.开启
     * @return array|DpAdminUser|null
     */
    public static function getByIsGroupAccess($isGroupAccess)
    {
        return static::getOneByFind(static::find()->findByIsGroupAccess($isGroupAccess));
    }

    /**
     * 获取指定用户权限记录
     *
     * @param integer $isUserAccess 用户权限 0.关闭,1.开启
     * @return array|DpAdminUser|null
     */
    public static function getByIsUserAccess($isUserAccess)
    {
        return static::getOneByFind(static::find()->findByIsUserAccess($isUserAccess));
    }

    /**
     * 获取指定系统用户记录
     *
     * @param integer $isSystem 系统用户 0.否,1.是
     * @return array|DpAdminUser|null
     */
    public static function getByIsSystem($isSystem)
    {
        return static::getOneByFind(static::find()->findByIsSystem($isSystem));
    }

    /**
     * 获取指定超级用户记录
     *
     * @param integer $isSuper 超级用户 0.否,1.是
     * @return array|DpAdminUser|null
     */
    public static function getByIsSuper($isSuper)
    {
        return static::getOneByFind(static::find()->findByIsSuper($isSuper));
    }

    /**
     * 获取指定备注记录
     *
     * @param string $note 备注
     * @return array|DpAdminUser|null
     */
    public static function getByNote($note)
    {
        return static::getOneByFind(static::find()->findByNote($note));
    }

    /**
     * 获取模糊备注记录
     *
     * @param string $note 备注
     * @return array|DpAdminUser|null
     */
    public static function getLikeByNote($note)
    {
        return static::getOneByFind(static::find()->findLikeByNote($note));
    }

    /**
     * 获取指定状态记录
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @return array|DpAdminUser|null
     */
    public static function getByStatus($status)
    {
        return static::getOneByFind(static::find()->findByStatus($status));
    }

    /**
     * 获取指定自增ID号列表记录
     *
     * @param integer $userId 自增ID号
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByUserId($userId, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByUserId($userId), $order, $page, $pageSize);
    }

    /**
     * 获取指定用户名列表记录
     *
     * @param string $username 用户名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByUsername($username, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByUsername($username), $order, $page, $pageSize);
    }

    /**
     * 获取模糊用户名列表记录
     *
     * @param string $username 用户名
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByUsername($username, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByUsername($username), $order, $page, $pageSize);
    }

    /**
     * 获取指定昵称列表记录
     *
     * @param string $nickname 昵称
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByNickname($nickname, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByNickname($nickname), $order, $page, $pageSize);
    }

    /**
     * 获取模糊昵称列表记录
     *
     * @param string $nickname 昵称
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByNickname($nickname, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByNickname($nickname), $order, $page, $pageSize);
    }

    /**
     * 获取指定哈希密码列表记录
     *
     * @param string $passwordHash 哈希密码
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByPasswordHash($passwordHash, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByPasswordHash($passwordHash), $order, $page, $pageSize);
    }

    /**
     * 获取模糊哈希密码列表记录
     *
     * @param string $passwordHash 哈希密码
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByPasswordHash($passwordHash, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByPasswordHash($passwordHash), $order, $page, $pageSize);
    }

    /**
     * 获取指定访问令牌列表记录
     *
     * @param string $passwordResetToken 访问令牌
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByPasswordResetToken($passwordResetToken, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByPasswordResetToken($passwordResetToken), $order, $page, $pageSize);
    }

    /**
     * 获取模糊访问令牌列表记录
     *
     * @param string $passwordResetToken 访问令牌
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByPasswordResetToken($passwordResetToken, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByPasswordResetToken($passwordResetToken), $order, $page, $pageSize);
    }

    /**
     * 获取指定验证码列表记录
     *
     * @param string $authKey 验证码
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByAuthKey($authKey, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByAuthKey($authKey), $order, $page, $pageSize);
    }

    /**
     * 获取模糊验证码列表记录
     *
     * @param string $authKey 验证码
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByAuthKey($authKey, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByAuthKey($authKey), $order, $page, $pageSize);
    }

    /**
     * 获取指定用户组id集,对应admin_group的id字段列表记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByGroupIds($groupIds, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByGroupIds($groupIds), $order, $page, $pageSize);
    }

    /**
     * 获取模糊用户组id集,对应admin_group的id字段列表记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListLikeByGroupIds($groupIds, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findLikeByGroupIds($groupIds), $order, $page, $pageSize);
    }

    /**
     * 获取指定启用用户组权限列表记录
     *
     * @param integer $isActiveGroupAccess 启用用户组权限 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByIsActiveGroupAccess($isActiveGroupAccess, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsActiveGroupAccess($isActiveGroupAccess), $order, $page, $pageSize);
    }

    /**
     * 获取指定启用用户权限列表记录
     *
     * @param integer $isActiveUserAccess 启用用户权限 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByIsActiveUserAccess($isActiveUserAccess, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsActiveUserAccess($isActiveUserAccess), $order, $page, $pageSize);
    }

    /**
     * 获取指定系统用户列表记录
     *
     * @param integer $isSystem 系统用户 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByIsSystem($isSystem, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsSystem($isSystem), $order, $page, $pageSize);
    }

    /**
     * 获取指定超级用户列表记录
     *
     * @param integer $isSuper 超级用户 0.否,1.是
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
     */
    public static function getListByIsSuper($isSuper, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByIsSuper($isSuper), $order, $page, $pageSize);
    }

    /**
     * 获取指定备注列表记录
     *
     * @param string $note 备注
     * @param string|null $order 排序
     * @param integer $page 页码
     * @param integer $pageSize 每页数量
     * @return array|DpAdminUser|null
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
     * @return array|DpAdminUser|null
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
     * @return array|DpAdminUser|null
     */
    public static function getListByStatus($status, $order = null, $page = 1, $pageSize = 20)
    {
        return static::getListByFind(static::find()->findByStatus($status), $order, $page, $pageSize);
    }

    /**
     * 获取指定自增ID号所有记录
     *
     * @param integer $userId 自增ID号
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByUserId($userId, $order = null)
    {
        return static::getAllByFind(static::find()->findByUserId($userId), $order);
    }

    /**
     * 获取指定用户名所有记录
     *
     * @param string $username 用户名
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByUsername($username, $order = null)
    {
        return static::getAllByFind(static::find()->findByUsername($username), $order);
    }

    /**
     * 获取模糊用户名所有记录
     *
     * @param string $username 用户名
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByUsername($username, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByUsername($username), $order);
    }

    /**
     * 获取指定昵称所有记录
     *
     * @param string $nickname 昵称
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByNickname($nickname, $order = null)
    {
        return static::getAllByFind(static::find()->findByNickname($nickname), $order);
    }

    /**
     * 获取模糊昵称所有记录
     *
     * @param string $nickname 昵称
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByNickname($nickname, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByNickname($nickname), $order);
    }

    /**
     * 获取指定哈希密码所有记录
     *
     * @param string $passwordHash 哈希密码
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByPasswordHash($passwordHash, $order = null)
    {
        return static::getAllByFind(static::find()->findByPasswordHash($passwordHash), $order);
    }

    /**
     * 获取模糊哈希密码所有记录
     *
     * @param string $passwordHash 哈希密码
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByPasswordHash($passwordHash, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByPasswordHash($passwordHash), $order);
    }

    /**
     * 获取指定访问令牌所有记录
     *
     * @param string $passwordResetToken 访问令牌
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByPasswordResetToken($passwordResetToken, $order = null)
    {
        return static::getAllByFind(static::find()->findByPasswordResetToken($passwordResetToken), $order);
    }

    /**
     * 获取模糊访问令牌所有记录
     *
     * @param string $passwordResetToken 访问令牌
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByPasswordResetToken($passwordResetToken, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByPasswordResetToken($passwordResetToken), $order);
    }

    /**
     * 获取指定验证码所有记录
     *
     * @param string $authKey 验证码
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByAuthKey($authKey, $order = null)
    {
        return static::getAllByFind(static::find()->findByAuthKey($authKey), $order);
    }

    /**
     * 获取模糊验证码所有记录
     *
     * @param string $authKey 验证码
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByAuthKey($authKey, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByAuthKey($authKey), $order);
    }

    /**
     * 获取指定用户组id集,对应admin_group的id字段所有记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByGroupIds($groupIds, $order = null)
    {
        return static::getAllByFind(static::find()->findByGroupIds($groupIds), $order);
    }

    /**
     * 获取模糊用户组id集,对应admin_group的id字段所有记录
     *
     * @param string $groupIds 用户组id集,对应admin_group的id字段
     * @param string|null $order 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByGroupIds($groupIds, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByGroupIds($groupIds), $order);
    }

    /**
     * 获取指定启用用户组权限所有记录
     *
     * @param integer $isActiveGroupAccess 启用用户组权限 0.否,1.是
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByIsActiveGroupAccess($isActiveGroupAccess, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsActiveGroupAccess($isActiveGroupAccess), $order);
    }

    /**
     * 获取指定启用用户权限所有记录
     *
     * @param integer $isActiveUserAccess 启用用户权限 0.否,1.是
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByIsActiveUserAccess($isActiveUserAccess, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsActiveUserAccess($isActiveUserAccess), $order);
    }

    /**
     * 获取指定系统用户所有记录
     *
     * @param integer $isSystem 系统用户 0.否,1.是
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByIsSystem($isSystem, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsSystem($isSystem), $order);
    }

    /**
     * 获取指定超级用户所有记录
     *
     * @param integer $isSuper 超级用户 0.否,1.是
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByIsSuper($isSuper, $order = null)
    {
        return static::getAllByFind(static::find()->findByIsSuper($isSuper), $order);
    }

    /**
     * 获取指定备注所有记录
     *
     * @param string $note 备注
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
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
     * @return array|DpAdminUser|null
     */
    public static function getAllLikeByNote($note, $order = null)
    {
        return static::getAllByFind(static::find()->findLikeByNote($note), $order);
    }

    /**
     * 获取指定状态所有记录
     *
     * @param integer $status 状态 0.禁用,1.启用,2.删除
     * @param string $order |null 排序
     * @return array|DpAdminUser|null
     */
    public static function getAllByStatus($status, $order = null)
    {
        return static::getAllByFind(static::find()->findByStatus($status), $order);
    }
}
