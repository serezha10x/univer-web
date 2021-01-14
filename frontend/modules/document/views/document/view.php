<?php

use common\helpers\ViewHelper;
use frontend\modules\document\models\DocumentProperty;
use frontend\modules\document\models\DocumentSection;
use frontend\modules\document\models\DocumentType;
use frontend\modules\document\models\Property;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $documents frontend\modules\document\models\Document[] */

if (count($documents) === 1) {
    $this->title = $documents[0]->document_name;
} else {
    $this->title = 'Документы';
}
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?php foreach($documents as $document) { ?>
    <div class="document-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Редактировать', ['update', 'id' => $document->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $document->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $document,
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
                        return $data->getSection()->one()['name'];
                    },
                ],
                [
                    'format' => 'html',
                    'attribute' => 'Контекстный вектор',
                    'value' => function ($data) {
                        return \common\helpers\VectorHelper::getStringFromVector($data->getVsm());
                    },
                ],
                [
                    'attribute' => 'Тип косинуса',
                    'value' => function ($data) {
                        return $data->getDocumentSection()['is_soft_similarity_chosen'] ?
                            DocumentSection::SOFT_SIMILARITY_TYPE :
                            DocumentSection::COMMON_SIMILARITY_TYPE;
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
<?php } ?>