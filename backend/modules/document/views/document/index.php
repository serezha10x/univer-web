<?php

use backend\modules\document\models\DocumentType;
use backend\modules\document\services\DocumentService;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\document\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
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
        <?= Html::a('Загрузить документ локально', ['upload-local'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Загрузить документ с сети', '/admin/document/document-upload/upload-web', ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Поиск документов', ['search'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Удалить документы по имени', ['delete-by-regex'], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Удалить все документы', ['delete-all'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?php if (Yii::$app->session->hasFlash('delete')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('delete') ?>
        </div>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
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
            [
                'attribute' => 'Тип документа',
                'value' => function ($data) {
                    return DocumentType::findOne(['id' => $data->document_type_id])->type;
                }
            ],
            'doc_source',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
