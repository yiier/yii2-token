<?php

namespace yiier\token\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%token_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property integer $token_id
 * @property string $token_value
 * @property string $method GET, POST, HEAD, PUT, PATCH, DELETE
 * @property string $url
 * @property integer $ip
 * @property integer $created_at
 */
class TokenLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%token_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'token_id', 'token_value', 'url', 'ip'], 'required'],
            [['user_id', 'token_id', 'method', 'created_at'], 'integer'],
            [['username', 'token_value', 'ip'], 'string', 'max' => 120],
            ['method', 'string', 'max' => 10],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'token_id' => Yii::t('app', 'Token ID'),
            'token_value' => Yii::t('app', 'Token Value'),
            'method' => Yii::t('app', '请求类型'),
            'url' => Yii::t('app', 'Url'),
            'ip' => Yii::t('app', 'Ip'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * 每次请求保存 log
     * @return mixed|string
     * @throws \Exception
     */
    public static function saveModel()
    {
        $model = new self;
        $token = Token::findModel();
        $model->setAttributes([
            'token_id' => $token->id,
            'token_value' => $token->value,
            'url' => \Yii::$app->request->absoluteUrl,
            'method' => \Yii::$app->request->method,
            'user_id' => $token->user_id,
            'username' => ArrayHelper::getValue(Yii::$app->user->identity, 'username'),
            'ip' => \Yii::$app->request->userIP,
            'created_at' => time()
        ]);
        return $model->save();
    }
}
