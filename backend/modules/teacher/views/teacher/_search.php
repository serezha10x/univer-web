<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\teacher\models\TeacherSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'fathername') ?>

    <?= $form->field($model, 'surname') ?>

    <?= $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'google_scholar') ?>

    <?php // echo $form->field($model, 'google_scholar_id') ?>

    <?php // echo $form->field($model, 'science_index') ?>

    <?php // echo $form->field($model, 'science_index_id') ?>

    <?php // echo $form->field($model, 'spin_code') ?>

    <?php // echo $form->field($model, 'sciverse_scopus') ?>

    <?php // echo $form->field($model, 'sciverse_scopus_id') ?>

    <?php // echo $form->field($model, 'scopus_author_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
