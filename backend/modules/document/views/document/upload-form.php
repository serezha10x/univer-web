<?php

use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $typrs array */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="form-group">
        <?= FileInput::widget([
            'model' => $model,
            'attribute' => 'uploadDocuments[]',
            'options' => [
                'multiple' => true,
                'showPreview' => true,
                'showCaption' => true,
            ]
        ]);?>
    </div>

    <label>Тип документа</label>
    <div class="form-group">
        <?= Select2::widget([
            'model' => $model,
            'name' => 'document_type_id',
            'value' => '',
            'data' => $types,
            'options' => ['multiple' => false, 'placeholder' => 'Тип документа']
        ]);?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Загрузить документ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
