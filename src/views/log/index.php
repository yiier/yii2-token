<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel yiier\token\models\TokenLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Token Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'token_id',
            [
                'attribute' => 'url',
                'filter' => Html::activeTextInput($searchModel, 'url', ['class' => 'form-control']),
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a("请求地址", $data->url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $data->url]);
                },
            ],
            [
                'attribute' => 'ip',
                'format' => 'raw',
                'value' => function ($data) {
                    return long2ip($data->ip);
                },
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ]
        ],
    ]); ?>

</div>
