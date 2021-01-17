<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\section\models\Section */

$this->title = 'Создать раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
