<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\document\models\Document */

$this->title = 'Загрузить документ';
$this->params['breadcrumbs'][] = ['label' => 'Документ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('upload-form', [
        'model' => $model,
    ]) ?>

</div>
