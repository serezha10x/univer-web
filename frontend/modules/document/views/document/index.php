<?php

use frontend\modules\document\models\DocumentType;
use frontend\modules\document\services\DocumentService;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\document\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documents';
$this->params['breadcrumbs'][] = $this->title;

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
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'document_name',
            [
                'format' => 'html',
                'attribute' => 'Преподаватели',
                'value' => function ($data) {
                    return DocumentService::getTeacherByDocTeacher($data->id);
                }
            ],
            'document_name',
            [
                'attribute' => 'Тип документа',
                'value' => function ($data) {
                    return DocumentType::findOne(['id' => $data->document_type_id])->type;
                }
            ],
            'file_name_before',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
