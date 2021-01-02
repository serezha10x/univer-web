<?php

namespace frontend\modules\teacher\controllers;

use common\exceptions\NotFoundTeacherException;
use frontend\modules\document\services\finder\GoogleScholarPublications;
use frontend\modules\teacher\models\TeacherIndicator;
use frontend\modules\teacher\services\DocsFinderWeb;
use Yii;
use frontend\modules\teacher\models\Teacher;
use frontend\modules\teacher\models\TeacherSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use function DI\value;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete', 'update-indications'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'update-indications'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teacher model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teacher();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateIndications($id)
    {
        $model = $this->findModel($id);
        $info_sites = [Teacher::GOOGLE_SCHOLAR, Teacher::SCIENCE_INDEX];
        $info = [];
        foreach ($info_sites as $info_site) {
            $info[] = $model->getIndications($info_site);
            $model->createOrUpdateIndications($info_site);
        }
        //$teacher = new Teacher();
        //echo '<pre>';var_dump($info);

        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $model
        ]);
    }
//
//    public function actionShowDocsWeb($id)
//    {
//        if (!Teacher::isIssetTeacher($id)) {
//            throw new NotFoundTeacherException();
//        }
//        $teacher = Teacher::findOne(['id' => $id]);
//
//        $docsFinder = new DocsFinderWeb();
//        $docs = $docsFinder->getDocs($teacher);
//
////        echo '<pre>';var_dump($docs); exit();
//        $dataProvider = new ArrayDataProvider ([
//            'allModels' => $docs,
////            'pagination' => [
////                'pageSize' => 20,
////            ],
//        ]);
//
//        return $this->render('show-docs', [
//            'dataProvider' => $dataProvider,
//            'docs' => $docs,
//            'teacher' => $teacher
//        ]);
//    }


    public function actionShowDocsWeb($id)
    {
        if (!Teacher::isIssetTeacher($id)) {
            throw new NotFoundTeacherException();
        }
        $teacher = Teacher::findOne(['id' => $id]);
        $code = substr($teacher->google_scholar, -12, 12);

        $googleScholarPublications = new GoogleScholarPublications($code);
        $publications = $googleScholarPublications->getPublications();
        $docs = $googleScholarPublications->convertToDocuments($publications);

        $dataProvider = new ArrayDataProvider ([
            'allModels' => $docs,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('show-publications', [
            'dataProvider' => $dataProvider,
            'docs' => $docs,
            'teacher' => $teacher
        ]);
    }

}
