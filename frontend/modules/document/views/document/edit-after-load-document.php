<?php

use frontend\modules\teacher\models\Teacher;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\document\models\Document */

$this->title = 'Редактиовать документ';
$this->params['breadcrumbs'][] = ['label' => 'Документ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Загрузка документа', 'url' => ['upload']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="document-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="document-edit-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($document, 'document_name')->
            input('text', ['value' => $document->document_name])->label('Название');
            ?>
        </div>

        <label>Тип документа</label>
        <div class="form-group">
            <?= Select2::widget([
                'model' => $document,
                'name' => 'document_type_id',
                'value' => '',
                'data' => $types,
                'options' => ['multiple' => false, 'placeholder' => 'Тип документа']
            ]);?>
        </div>

        <label>Преподаватели</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'teachers[]',
                'value' => Teacher::getTeachersIdsBySurname($foundTeachers),
                'data' => $teachers,
                'options' => ['multiple' => true, 'placeholder' => 'Преподаватели']
            ]);?>
        </div>

        <label>Ключевые слова</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'keywords[]',
                'value' => array_keys($keywords),
                'data' => $keywords,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Ключевые слова']
            ]);?>
        </div>

        <label>ФИО</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'fios[]',
                'value' => array_keys($fios),
                'data' => $fios,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'ФИО'],
            ]);?>
        </div>

        <label>Элестронные адреса</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'emails[]',
                'value' => array_keys($emails),
                'data' => $emails,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Emails'],
            ]);?>
        </div>

        <label>Даты</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'dates[]',
                'value' => array_keys($dates),
                'data' => $dates,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Даты'],
            ]);?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Редактировать документ', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
