<?php

namespace yiier\token\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%token}}".
 *
 * @property integer $id
 * @property string $provider 授权提供商
 * @property integer $user_id
 * @property string $username
 * @property string $value
 * @property string $ip ip
 * @property integer $status 状态 10 正常 0删除
 * @property integer $expires_in 有效时间，单位秒
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expired_at 过期时间戳
 */
class Token extends ActiveRecord
{
    const EXPIRES_IN = 7200;

    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%token}}';
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'status', 'expires_in', 'created_at', 'updated_at', 'expired_at'], 'integer'],
            [['provider'], 'string', 'max' => 20],
            [['username', 'value', 'ip'], 'string', 'max' => 120],
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
            $this->ip = \Yii::$app->request->userIP;
            $this->expires_in = self::EXPIRES_IN;
            $this->expired_at = time() + self::EXPIRES_IN;
            return true;
        } else {
            return false;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'provider' => Yii::t('app', 'Provider'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'value' => Yii::t('app', 'Value'),
            'ip' => Yii::t('app', 'Ip'),
            'status' => Yii::t('app', 'Status'),
            'expires_in' => Yii::t('app', 'Expires In'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'expired_at' => Yii::t('app', 'Expired At'),
        ];
    }

    /**
     * 根据当前 access-token 返回Model
     * @param null $accessToken
     * @return Token|false
     */
    public static function findModel($accessToken = null)
    {
        $accessToken = $accessToken ?: Yii::$app->request->get('access-token');
        return self::find()->where(['value' => $accessToken])->limit(1)->one();
    }

    /**
     * 返回当前未过期的 token
     * @param null $token
     * @return Token|false
     */
    public static function findActiveByToken($token = null)
    {
        $token = $token ?: Yii::$app->request->get('access-token');
        return self::find()
            ->where(['value' => $token])
            ->andWhere(['>=', 'expired_at', time()])
            ->limit(1)
            ->one();
    }


    /**
     * 返回当前用户使用的 token
     * @param null $userId
     * @return Token|false
     */
    public static function findActiveByUserId($userId = null)
    {
        $userId = $userId ?: Yii::$app->user->id;
        return self::find()
            ->where(['user_id' => $userId])
            ->andWhere(['>=', 'expired_at', time()])
            ->limit(1)
            ->one();
    }


    /**
     * 返回当前用户的 token，没有的话新建一条
     * @param null $userId
     * @return Token|false
     */
    public static function getToken($userId = null)
    {
        $userId = $userId ?: Yii::$app->user->id;
        if (!$model = self::findActiveByUserId($userId)) {
            $model = new self();
            $model->user_id = $userId;
            $model->save();
            return $model;
        }
        return $model;
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
