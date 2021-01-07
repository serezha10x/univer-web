<?php

namespace frontend\modules\document\controllers;

use common\helpers\CommonHelper;
use common\services\wiki\WikipediaApi;
use frontend\modules\document\handlers\DocumentHandler;
use frontend\modules\document\models\Document;
use frontend\modules\document\models\DocumentProperty;
use frontend\modules\document\models\DocumentSearch;
use frontend\modules\document\models\DocumentTeacher;
use frontend\modules\document\models\DocumentType;
use frontend\modules\document\models\Keyword;
use frontend\modules\document\models\Property;
use frontend\modules\document\models\UploadDocumentForm;
use frontend\modules\document\models\UploadWebDocumentForm;
use frontend\modules\document\services\DocumentService;
use frontend\modules\section\models\Section;
use frontend\modules\section\service\TensorHandler;
use frontend\modules\teacher\models\Teacher;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
//                    [
//                        'allow' => true,
//                        //'actions' => ['create', 'update', 'delete'],
//                        'actions' => ['delete'],
//                        'roles' => ['@'],
//                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'update', 'view', 'update-indications'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'teachers_by_doc' => DocumentService::getTeacherByDocTeacher($id),
        ]);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new UploadDocumentForm();

        if (Yii::$app->request->isPost) {
            $model->document = UploadDocumentForm::getInstances($model, 'document');
            if ($model->upload()) {
                // file is uploaded successfully
                Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
                return true;
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUploadLocal()
    {
        $model = new UploadDocumentForm();

        if (Yii::$app->request->isPost) {
            $model->upload_document = UploadedFile::getInstance($model, 'upload_document');
            $document = $model->upload();
            if ($document !== null) {
                // file is uploaded successfully
                $handler = new DocumentHandler($document);
                $handler->textHandle();
                $document->setCsv();

                Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
                $this->redirect(Url::toRoute(['update', 'id' => $document->id]));
            }
        }

        $types = ArrayHelper::map(DocumentType::find()->all(), 'id', 'type');

        return $this->render('create', ['model' => $model, 'types' => $types]);
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $document = $this->findModel($id);
            $document->updateProperties($request->post('keywords'), Property::getIdByProperty(Property::KEY_WORDS));
            $document->updateProperties($request->post('dates'), Property::getIdByProperty(Property::DATES));
            $document->updateProperties($request->post('fios'), Property::getIdByProperty(Property::FIO));
            $document->updateProperties($request->post('emails'), Property::getIdByProperty(Property::EMAIL));
            $document->updateTeachers($request->post('teachers'));
            $document->load($request->post());
            $document->document_type_id = $request->post('document_type_id');
            $document->section_id = $request->post('section_id');
            $document->save();

            return $this->redirect(['view', 'id' => $id]);
        } else {
            $document = Document::findOne($id);

            $teachers = ArrayHelper::map(Teacher::find()->all(), 'id', 'surname');
            $types = ArrayHelper::map(DocumentType::find()->all(), 'id', 'type');

            $properties = [
                'keywords' => Property::KEY_WORDS,
                'fios' => Property::FIO,
                'emails' => Property::EMAIL,
                'dates' => Property::DATES,
                'foundTeachers' => Property::TEACHER
            ];

            $propertiesValue = [];
            foreach ($properties as $key => $property) {
                $propertiesValue += [$key => ArrayHelper::map(DocumentProperty::find()->where([
                    'document_id' => $id,
                    'property_id' => Property::getIdByProperty($property)
                ])->all(), 'id', 'value')];
            }

            return $this->render('edit-after-load-document', array_merge([
                'teachers' => $teachers,
                'document' => $document,
                'types' => $types,
                'sections' => $document->getSectionMap()
            ], $propertiesValue));
        }
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $documentTeacher = DocumentTeacher::find()->where(['document_id' => $id])->all();
        $keywords = Keyword::find()->where(['document_id' => $id])->all();
        $properties = DocumentProperty::find()->where(['document_id' => $id])->all();

        foreach ($documentTeacher as $item) {
            $item->delete();
        }
        foreach ($keywords as $keyword) {
            $keyword->delete();
        }
        foreach ($properties as $property) {
            $property->delete();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSearch()
    {
        $tensorHandler = new TensorHandler(Section::findOne(1),
            'PHP, JAVA КОМПИЛЯТОР');
        $tensorHandler->getVsm();
//        $tensor = $t->getT(Section::findOne(1));
//        $b = $t->additiveConvolution3($tensor);
//        $a = $t->getQueryVsm('PHP, JAVA КОМПИЛЯТОР');
//        $t->getQ($b, $a);
//        $wiki = new WikipediaApi();
//        echo($wiki->wiktionary('Java'));die;
        $request = Yii::$app->request;
        $uploadForm = new UploadWebDocumentForm();

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {

            $userQuery = ['PHP' => 1];//$request->post()['search'];

            $document = new Document();
            $document->setVsm($userQuery);
            $suitableSections = Section::getSectionsForDocument($document);

//            $result = Document::find()->where(['LIKE', 'vsm', "PHP"])->all();

            var_dump($suitableSections);
            die;

            $dataProvider = new ArrayDataProvider ([
                'allModels' => $docs,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('show-upload-web-document', [
                'uploadForm' => $uploadForm,
                'docs' => $docs,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('/document-upload/upload-web-form', ['model' => new UploadWebDocumentForm()]);
    }
}
