<?php

namespace wsl\rbac\models;

use Exception;
use Yii;
use yii\base\NotSupportedException;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "dp_admin_user".
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
 * @property DpAdminUserMenuRelation[] $dpAdminUserMenuLinks
 * @property DpAdminMenu[] $menus
 */
class DpAdminUser extends ActiveRecord implements IdentityInterface
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
     * @var string 原始密码
     */
    public $source_password = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_admin_user';
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
        return $this->hasMany(DpAdminUserMenuRelation::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(DpAdminMenu::className(), ['menu_id' => 'menu_id'])->viaTable('{{%admin_user_menu_relation}}', ['user_id' => 'user_id']);
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
        try {
            return Yii::$app->security->validatePassword($password, $this->password_hash);
        } catch (Exception $e) {
            return false;
        }
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
}
