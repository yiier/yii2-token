<?php

namespace yiier\token;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yiier\token\controllers';

    public function init()
    {
        parent::init();
        // todo 待完成
        if (!isset(Yii::$app->i18n->translations['token'])) {
            Yii::$app->i18n->translations['token'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@yiier/token/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'token' => 'token.php',
                ]
            ];
        }
    }
}
