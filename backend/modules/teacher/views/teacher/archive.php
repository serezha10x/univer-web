<?php

use backend\modules\document\models\DocumentType;
use backend\modules\document\services\DocumentService;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\document\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Архив';
$this->params['breadcrumbs'][] = $this->title;

?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="screen" />
<link href="https://github.com/FortAwesome/Font-Awesome/blob/master/web-fonts-with-css/css/fontawesome.css" rel="stylesheet">
<style>
    .grid-view td {
        white-space: nowrap;
    }

    .grid-view td .wrap {
        white-space: pre-wrap;
    }
</style>
<div class="document-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $archive ?>

</div>
