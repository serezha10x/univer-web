<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\document\models\Document */

$this->title = 'Редактиовать документ';
$this->params['breadcrumbs'][] = ['label' => 'Документ', 'url' => ['index']];
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

        <div class="form-group">
            <?= Select2::widget([
                'model' => $document,
                'name' => 'document_type_id',
                'value' => '',
                'data' => $types,
                'options' => ['multiple' => false, 'placeholder' => 'Тип документа']
            ]);?>
        </div>

        <div class="form-group">
            <?= Select2::widget([
                'name' => 'teachers[]',
                'value' => '',
                'data' => $teachers,
                'options' => ['multiple' => true, 'placeholder' => 'Преподаватели']
            ]);?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Редактировать документ', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
