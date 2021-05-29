<?php

use backend\modules\teacher\models\Teacher;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $document backend\modules\document\models\Document */

$this->title = 'Редактировать документ';
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

        <label>Темы</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'theme[]',
                'value' => array_keys($theme),
                'data' => $theme,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Ключевые слова']
            ]); ?>
        </div>

        <label>Тип документа</label>
        <div class="form-group">
            <?= Select2::widget([
                'model' => $document,
                'name' => 'document_type_id',
                'value' => $document->document_type_id,
                'data' => $types,
                'options' => ['multiple' => false, 'placeholder' => 'Тип документа']
            ]); ?>
        </div>

        <label>Тематический раздел по обычному косинусу:</label>
        <div class="form-group">
            <?php if (isset($document->section_id)) { ?>
                <?= Select2::widget([
                    'model' => $document,
                    'name' => 'section_id',
                    'value' => $document->section_id,
                    'data' => $cosineSections,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } else { ?>
                <?= Select2::widget([
                    'name' => 'section_id',
                    'value' => $document->section_id,
                    'data' => $cosineSections,
                    'maintainOrder' => true,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } ?>
        </div>

        <?php
        if ((int) \backend\modules\settings\models\Settings::getSettings('SOFT_COSINE_ENABLE')) {
            echo "<label>Тематический раздел по мягкому косинусу:</label>";
            echo '<div class="form-group">';
            if (isset($document->section_id)) {
                echo Select2::widget([
                    'model' => $document,
                    'name' => 'section_id_soft',
                    'value' => $document->section_id,
                    'data' => $softCosineSections,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]);
            } else {
                echo Select2::widget([
                    'name' => 'section_id_soft',
                    'value' => $document->section_id,
                    'data' => $softCosineSections,
                    'maintainOrder' => true,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]);
            }
            echo '</div>';
        }
    ?>

        <label>Тематический раздел по контекстному методу:</label>
        <div class="form-group">
            <?php if (isset($document->section_id)) { ?>
                <?= Select2::widget([
                    'model' => $document,
                    'name' => 'section_id_context',
                    'value' => $document->section_id,
                    'data' => $contextSections,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } else { ?>
                <?= Select2::widget([
                    'name' => 'section_id_context',
                    'value' => $document->section_id,
                    'data' => $contextSections,
                    'maintainOrder' => true,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } ?>
        </div>

        <label>Тематический раздел по среднему взвешенному методу:</label>
        <div class="form-group">
            <?php if (isset($document->section_id)) { ?>
                <?= Select2::widget([
                    'model' => $document,
                    'name' => 'section_id_avg',
                    'value' => $document->section_id,
                    'data' => $avgSections,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } else { ?>
                <?= Select2::widget([
                    'name' => 'section_id_avg',
                    'value' => $document->section_id,
                    'data' => $avgSections,
                    'maintainOrder' => true,
                    'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
                ]); ?>
            <?php } ?>
        </div>

        <label>Метод обработки при сохранении раздела:</label>
        <div class="form-group">
            <?= Select2::widget([
                'value' => 'cosine',
                'name' => 'methodType',
                'data' => $methodType,
                'options' => ['multiple' => false, 'placeholder' => 'Выберите тематический раздел'],
            ]); ?>
        </div>

        <?php

//        echo SwitchInput::widget([
//            'name' => 'similar_type',
//            'value' => (bool) $document->getDocumentSection()->is_soft_similarity_chosen,
//            'indeterminateValue' => true,
//            'pluginOptions' => [
//                'handleWidth' => 160,
//                'onText' => 'Мягкий косинус',
//                'offText' => 'Обычный косинус',
//            ]
//        ]);
//        ?>

        <label>Преподаватели</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'teachers[]',
                'value' => Teacher::getTeachersIdsBySurname($foundTeachers),
                'data' => $teachers,
                'options' => ['multiple' => true, 'placeholder' => 'Преподаватели']
            ]); ?>
        </div>

        <label>Ключевые слова</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'keywords[]',
                'value' => array_keys($keywords),
                'data' => $keywords,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Ключевые слова']
            ]); ?>
        </div>

        <label>ФИО</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'fios[]',
                'value' => array_keys($fios),
                'data' => $fios,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'ФИО'],
            ]); ?>
        </div>

        <label>Электронные адреса</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'emails[]',
                'value' => array_keys($emails),
                'data' => $emails,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Emails'],
            ]); ?>
        </div>

        <label>Даты</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'dates[]',
                'value' => array_keys($dates),
                'data' => $dates,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Даты'],
            ]); ?>
        </div>

        <label>Литература:</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'literature[]',
                'value' => array_keys($literature),
                'data' => $literature,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Литература'],
            ]); ?>
        </div>

        <label>Аннотации:</label>
        <div class="form-group">
            <?= Select2::widget([
                'name' => 'annotation[]',
                'value' => array_keys($annotation),
                'data' => $annotation,
                'maintainOrder' => true,
                'options' => ['multiple' => true, 'placeholder' => 'Аннотации'],
            ]); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Редактировать документ', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
