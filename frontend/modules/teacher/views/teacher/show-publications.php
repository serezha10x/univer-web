<?php

use frontend\modules\document\models\DocumentType;
use frontend\modules\document\services\DocumentService;
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
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'description',
        [
            'format' => 'html',
            'value' => function ($data) {
                return Html::a("Ссылка", $data->document_name);
            }
        ],
        'year',
        ['class' => 'yii\grid\ActionColumn'],
    ]
]); ?>

    </div>

