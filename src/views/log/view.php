<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model yiier\token\models\TokenLog */

$this->title = 'Token Log 详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Token Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-log-view">
    <p>
        <?= Html::a('上一页', ['view', 'id' => $model->id + 1], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('下一页', ['view', 'id' => $model->id - 1], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'token_id',
            'url:url',
            'method',
            'user_id',
            'username',
            'data',
            'ip',
            'created_at:datetime',
        ],
    ]) ?>

</div>
