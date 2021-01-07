<?php

use common\helpers\ViewHelper;
use frontend\modules\document\models\DocumentProperty;
use frontend\modules\document\models\DocumentType;
use frontend\modules\document\models\Property;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\document\models\Document */

$this->title = $model->document_name;
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="document-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'document_name',
            [
                'attribute' => 'Тип документа',
                'value' => function ($data) {
                    $type = DocumentType::findOne(['id' => $data->documentType])->type;
                    return $type;
                },
            ],
            [
                'format' => 'html',
                'attribute' => 'Тематический раздел',
                'value' => function ($data) {
                    return \common\helpers\VectorHelper::getStringFromVector($data->getVsm());
                },
            ],
            [
                'attribute' => 'Контекстный вектор',
                'value' => function ($data) {
                    return $data->section->sections;
                },
            ],
            [
                'format' => 'html',
                'attribute' => 'Преподаватели',
                'value' => $teachers_by_doc,
            ],
            [
                'attribute' => 'Ключевые слова',
                'value' => function ($data) {
                    $keywords = DocumentProperty::getValuesByProperty($data->id, Property::KEY_WORDS);
                    return ViewHelper::formDataToDetailView($keywords);
                },
            ],
            [
                'attribute' => 'ФИО',
                'value' => function ($data) {
                    $keywords = DocumentProperty::getValuesByProperty($data->id, Property::FIO);
                    return ViewHelper::formDataToDetailView($keywords);
                },
            ],
            [
                'attribute' => 'Электронные адреса',
                'value' => function ($data) {
                    $keywords = DocumentProperty::getValuesByProperty($data->id, Property::EMAIL);
                    return ViewHelper::formDataToDetailView($keywords);
                },
            ],
            [
                'attribute' => 'Даты',
                'value' => function ($data) {
                    $keywords = DocumentProperty::getValuesByProperty($data->id, Property::DATES);
                    return ViewHelper::formDataToDetailView($keywords);
                },
            ],
            'doc_source'
        ],
    ]) ?>

</div>
