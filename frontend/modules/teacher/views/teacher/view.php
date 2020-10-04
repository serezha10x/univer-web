<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\teacher\models\Teacher */

$fullname = "$model->surname $model->name $model->fathername";
$this->title = $fullname;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель', 'url' => ['index']];
$this->params['breadcrumbs'][] = $fullname;
\yii\web\YiiAsset::register($this);
?>
<div class="teacher-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить показатели', ['update-indications', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Поиск статей в сети', ['show-docs-web', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Поиск статей в хранилище', ['update-indications', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'fathername',
            'surname',
            'position:ntext',
            'google_scholar:ntext',
            'science_index',
            [
                'attribute' => 'Количество публикаций на Google Scholar',
                'value' => $model->googleScholar->num_publication,
            ],
            [
                'attribute' => 'Количество цитирований на Google Scholar',
                'value' => $model->googleScholar->num_citations,
            ],
            [
                'attribute' => 'Индекс Хирша на Google Scholar',
                'value' => $model->googleScholar->index_hirsha,
            ],

            [
                'attribute' => 'Количество публикаций на ELibrary',
                'value' => $model->scienceIndex->num_publication,
            ],
            [
                'attribute' => 'Количество цитирований на ELibrary ',
                'value' => $model->scienceIndex->num_citations,
            ],
            [
                'attribute' => 'Индекс Хирша на Google ELibrary',
                'value' => $model->scienceIndex->index_hirsha,
            ],
//            'science_index:ntext',
//            'science_index_id',
//            'spin_code:ntext',
//            'sciverse_scopus:ntext',
//            'sciverse_scopus_id',
//            'scopus_author_id:ntext',
        ],
    ]) ?>

</div>
