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
//        [
//            'format' => 'html',
//            'value' => function ($data) {
//                return Html::a('<i>BTN</i>', ['show-doc-web', ['url' => 'teacher/teacher/download-doc-web']], ['class' => 'fas fa-save']);
//            }
//        ],
        'document_name',
//        [
//            'attribute' => 'Тип документа',
//            'value' => function ($data) {
//                return DocumentType::findOne(['id' => $data->document_type_id])->type;
//            }
//        ],
//        'file_name_before',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

    </div>

