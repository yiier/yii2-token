<?php

namespace yiier\token;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yiier\token\controllers';

    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['token'])) {
            Yii::$app->i18n->translations['token'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@yiier/token/messages'
            ];
        }
        // custom initialization code goes here
    }
}
