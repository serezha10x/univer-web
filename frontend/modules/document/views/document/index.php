<?php

use frontend\modules\document\services\DocumentService;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\document\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documents';
$this->params['breadcrumbs'][] = $this->title;
?>
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
            'id',
            'document_name',
            [
                'attribute' => 'Преподаватели',
                'class' => 'yii\grid\DataColumn', // может быть опущено, поскольку является значением по умолчанию
                'value' => function($data) {
                    return DocumentService::getTeacherByDocTeacher($data->id);
                }
            ],
            'document_name',
            'document_type_id',
            'file_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
