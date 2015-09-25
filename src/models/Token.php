<?php

namespace yiier\token\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $value
 * @property integer $ip
 * @property integer $status
 * @property integer $expires_in
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expired_at
 */
class Token extends ActiveRecord
{
    const EXPIRES_IN = 7200;

    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'value', 'ip', 'expires_in', 'created_at', 'updated_at', 'expired_at'], 'required'],
            [['user_id', 'ip', 'status', 'expires_in', 'created_at', 'updated_at', 'expired_at'], 'integer'],
            [['username', 'value'], 'string', 'max' => 255]
        ];
    }


    /**
     * 生成uuid
     * @return mixed
     */
    public function makeToken()
    {
        return md5(uniqid(mt_rand()));
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->value = $this->makeToken();
            $this->ip = ip2long(\Yii::$app->request->userIP);
            $this->expires_in = self::EXPIRES_IN;
            $this->expired_at = time() + self::EXPIRES_IN;
            return true;
        } else {
            return false;
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'username' => Yii::t('common', 'Username'),
            'value' => Yii::t('common', 'Value'),
            'ip' => Yii::t('common', 'Ip'),
            'status' => Yii::t('common', 'Status'),
            'expires_in' => Yii::t('common', 'Expires In'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'expired_at' => Yii::t('common', 'Expired At'),
        ];
    }

    /**
     * 根据当前 access_token 返回Model
     * @return bool|mixed
     */
    public static function findModel()
    {
        $get = Yii::$app->request->get();
        if (!empty($get['access_token'])) {
            return self::find()->where(['value' => $get['access_token']])->one();
        }
        return false;
    }

    public function getStatus()
    {
        return [
            '' => '全部',
            self::STATUS_ACTIVE => '正常',
            self::STATUS_DELETE => '禁用 ',
        ];
    }
}
