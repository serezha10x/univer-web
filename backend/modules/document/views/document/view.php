<?php

use backend\modules\document\models\DocumentProperty;
use backend\modules\document\models\DocumentSection;
use backend\modules\document\models\DocumentType;
use backend\modules\document\models\Property;
use backend\modules\document\services\reader\IReader;
use common\helpers\ViewHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $documents backend\modules\document\models\Document[] */

if (count($documents) === 1) {
    $this->title = $documents[0]->document_name;
} else {
    $this->title = 'Документы';
}
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

    <h1><?= Html::encode($this->title) ?></h1>

<?php foreach ($documents as $document) { ?>
    <div class="document-view">
        <p>
            <?= Html::a('Редактировать', ['update', 'id' => $document->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $document->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= "<p>Найдено объектов: " . $document->getNumProperties() . "</p>" ?>
            <?= "<p>Время обработки: " . $document->tth . "</p>" ?>
            <?= "<p>Тип обработки: " . IReader::readTypeNames[$document->read_type] . "</p>" ?>
            <?= "<p>Страницы: " . $document->pages . "</p>" ?>
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
//                [
//                    'attribute' => 'Метод обработки при сохранении раздела',
//                    'value' => function ($data) {
//                        /* @var \backend\modules\document\models\Document $data */
//                        return $data->getDocumentSection()->method_chosen;
//                    },
//                ],
                [
                    'format' => 'html',
                    'attribute' => 'Преподаватели',
                    'value' => $teachers_by_doc,
                ],
                [
                    'attribute' => 'Темы',
                    'value' => function ($data) {
                        $keywords = DocumentProperty::getValuesByProperty($data->id, Property::THEME);
                        return ViewHelper::formDataToDetailView($keywords);
                    },
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
                'doc_source',
                [
                    'attribute' => 'Литература',
                    'value' => function ($data) {
                        $keywords = DocumentProperty::getValuesByProperty($data->id, Property::LITERATURE);
//                        var_dump($data->id,($keywords));die;
                        return ViewHelper::formDataToDetailView($keywords);
                    },
                ],
                'doc_source',
                [
                    'attribute' => 'Аннотации',
                    'value' => function ($data) {
                        $keywords = DocumentProperty::getValuesByProperty($data->id, Property::ANNOTATIONS);
                        return ViewHelper::formDataToDetailView($keywords);
                    },
                ],
                'tth'
            ],
        ]) ?>

    </div>
<?php } ?>