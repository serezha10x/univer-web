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
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Название документа',
                'value' => function ($data, $key) {
                    return $key;
                },
            ],
            [
                'attribute' => 'Обычный косинус',
                'value' => function ($data) {
                    return $data;
                },
            ],
//            [
//                'attribute' => 'Мягкий косинус',
//                'value' => function ($data) {
//                    return $data['soft_similarity'];
//                },
//            ],
        ]
    ]); ?>

</div>