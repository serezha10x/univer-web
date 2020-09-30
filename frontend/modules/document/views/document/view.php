<?php

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
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                'format' => 'html',
                'attribute' => 'Преподаватели',
                'value' => $teachers_by_doc,
            ],
            [
                'attribute' => 'Ключевые слова',
                'value' => function ($data) {
                    $keywords_str = '';
                    foreach ($data->keywords as $keyword) {
                        $keywords_str .= ($keyword->value . ', ');
                    }
                    $keywords_str = rtrim($keywords_str, ', ');
                    return $keywords_str;
                },
            ],
        ],
    ]) ?>

</div>
