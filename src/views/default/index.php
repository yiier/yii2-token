<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use kartik\icons\Icon;
use yiier\token\models\Token;

/* @var $this yii\web\View */
/* @var $searchModel yiier\token\models\TokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tokens');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_id',
            'username',
            'provider',
            'value',
            'expires_in',
            [
                'format' => 'raw',
                'attribute' => 'ip',
                'value' => function ($data) {
                    return $data->ip;
                },
            ],
            'created_at:datetime',
            'expired_at:datetime',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', Token::getStatus(), ['class' => 'form-control']),
                'value' => function ($data) {
                    return Token::getStatus()[$data->status];
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{status}',
                'buttons' => [
                    // 自定义按钮
                    'status' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('app', 'Change Status'),
                            'aria-label' => Yii::t('app', 'Change Status'),
                            'data-pjax' => '0',
                        ];
                        return Html::a(HTML::tag('i', '', ['class' => 'glyphicon glyphicon-transfer']), $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
