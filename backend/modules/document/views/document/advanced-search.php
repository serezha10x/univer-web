<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<?= $form->field($model, 'search_query')
    ->input(\yii\widgets\MaskedInput::className(), ['placeholder' => 'Поисковый запрос'])
    ->label("Поисковый запрос:") ?>

<label>Тип документа:</label>
<div class="form-group">
    <?= Select2::widget([
        'model' => $model,
        'name' => 'document_type',
        'value' => '',
        'data' => $types,
        'options' => ['multiple' => false, 'placeholder' => 'Тип документа']
    ]); ?>
</div>

<?= $form->field($model, 'author')
    ->input(\yii\widgets\MaskedInput::className(), ['placeholder' => 'Автор'])
    ->label("Автор:") ?>

<?php

echo $form->field($model, 'year')->widget(DatePicker::classname(), [
    'pluginOptions' => [
        'autoclose' => true,
        'startView' => 'year',
        'minViewMode' => 'years',
        'format' => 'yyyy'
    ]
])->label('Год:') ?>

<div class="form-group">
    <?= Html::submitButton('ПОИСК', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('СБРОСИТЬ', ['class' => 'btn btn-outline-secondary']) ?>
</div>

<?php
ActiveForm::end();
?>
