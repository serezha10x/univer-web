<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\teacher\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доступные для скачивания документы';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if( Yii::$app->session->hasFlash('service') ): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('service'); ?>
    </div>
<?php endif;?>

<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'document_name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    $linkTitle = strlen($model->doc_source) > 65
                        ? substr($model->doc_source, 0, 62) . '...'
                        : $model->doc_source;
                    return Html::a($linkTitle, $model->doc_source,
                        ['class' => 'profile-link', 'target' => '_blank']);
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{create}',
                'buttons' => [
                    'create' => function ($url, $model) {
                        $id = \common\helpers\CommonHelper::getUrlQuery($url, 'id');
                        $session = Yii::$app->session;
                        $session['documentToLoad_' . $id] = $model;

                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                            'title' => Yii::t('yii', 'Добавить документ'),
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
