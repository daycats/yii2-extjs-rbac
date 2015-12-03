<?php

namespace wsl\rbac\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for table "dp_config".
 *
 * @property string $name
 * @property string $value
 * @property integer $status
 */
class DpConfig extends ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dp_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['value'], 'string'],
            [['status'], 'integer'],
            [['value'], 'default', 'value' => ''],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'value' => '值',
            'status' => '状态',
        ];
    }

    /**
     * @inheritdoc
     * @return DpConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DpConfigQuery(get_called_class());
    }

    /**
     * 替换指定名称数据
     *
     * @param string $name 名称
     * @param string $value 值
     * @param int $status 状态
     * @return bool
     */
    public static function replaceByName($name, $value, $status)
    {
        $obj = static::find()
            ->findByName($name)
            ->one();
        if (!$obj) {
            $obj = new static();
        }
        $obj->setAttributes([
            'name' => $name,
            'value' => $value,
            'status' => $status,
        ]);

        return $obj->save();
    }

    /**
     * 获取指定名称记录
     *
     * @param string $name 名称
     * @return array|DpConfig|null
     */
    public static function getByName($name)
    {
        return static::find()
            ->findByName($name)
            ->asArray()
            ->one();
    }

    /**
     * 获取指定名称值
     *
     * @param string $name 名称
     * @return array|DpConfig|null
     */
    public static function getValueByName($name)
    {
        $data = static::getByName($name);

        return isset($data['value']) ? $data['value'] : null;
    }
}