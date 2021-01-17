<?php

use yii\grid\GridView;
use yii\helpers\Html;

?>

<style>
    .grid-view td {
        white-space: nowrap;
    }

    .grid-view td .wrap {
        white-space: pre-wrap;
    }
</style>

<div class="document-index">

<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Загрузить документ', ['upload'], ['class' => 'btn btn-success']) ?>
</p>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'description',
        'year',
        ['class' => 'yii\grid\ActionColumn'],
    ]
]); ?>

</div>