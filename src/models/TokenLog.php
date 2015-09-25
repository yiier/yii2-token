<?php

namespace yiier\token\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%token_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property integer $token_id
 * @property string $token_value
 * @property string $url
 * @property integer $ip
 * @property integer $created_at
 */
class TokenLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%token_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'token_id', 'token_value', 'url', 'ip', 'created_at'], 'required'],
            [['user_id', 'token_id', 'ip', 'created_at'], 'integer'],
            [['username', 'token_value', 'url'], 'string', 'max' => 255]
        ];
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
            'token_id' => Yii::t('common', 'Token ID'),
            'token_value' => Yii::t('common', 'Token Value'),
            'url' => Yii::t('common', 'Url'),
            'ip' => Yii::t('common', 'Ip'),
            'created_at' => Yii::t('common', 'Created At'),
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
        $shop = Token::findModel();
        $model->setAttributes([
            'token_id' => Token::tokenId(),
            'url' => \Yii::$app->request->absoluteUrl,
            'shop_id' => $shop->user->shop_id,
            'shop_name' => $shop->user->shop_name,
            'data' => \Yii::$app->request->rawBody,
            'ip' => ip2long(\Yii::$app->request->userIP),
        ]);
        return $model->save();
    }
}
