<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\teacher\models\Teacher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'google_scholar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'google_scholar_id')->textInput() ?>

    <?= $form->field($model, 'science_index')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'science_index_id')->textInput() ?>

    <?= $form->field($model, 'spin_code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sciverse_scopus')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sciverse_scopus_id')->textInput() ?>

    <?= $form->field($model, 'scopus_author_id')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
