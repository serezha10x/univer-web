<?php

namespace backend\modules\document\controllers;

use backend\modules\document\handlers\DocumentHandler;
use backend\modules\document\models\AdvancedSearch;
use backend\modules\document\models\Document;
use backend\modules\document\models\DocumentProperty;
use backend\modules\document\models\DocumentSearch;
use backend\modules\document\models\DocumentTeacher;
use backend\modules\document\models\DocumentType;
use backend\modules\document\models\Keyword;
use backend\modules\document\models\Property;
use backend\modules\document\models\UploadDocumentForm;
use backend\modules\document\models\UploadWebDocumentForm;
use backend\modules\document\services\DocumentService;
use backend\modules\document\services\vsm\AvgSimilarity;
use backend\modules\document\services\vsm\ContextSimilarity;
use backend\modules\document\services\vsm\CosineSimilarity;
use backend\modules\document\services\vsm\SoftCosineSimilarity;
use backend\modules\document\services\vsm\VsmSimilarity;
use backend\modules\section\models\Section;
use backend\modules\settings\models\Settings;
use backend\modules\teacher\models\Teacher;
use common\helpers\CommonHelper;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
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
                'only' => ['index', 'create', 'update', 'delete', 'update-indications'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionAdvancedSearch()
    {
        $model = new AdvancedSearch();
        $types = [
            'Учебно-методическое издание',
            'Научная статья'
        ];
        return $this->render('advanced-search', [
            'model' => $model,
            'types' => $types,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @param null $ids
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = null, $ids = null)
    {
        if ($id !== null) {
            return $this->render('view', [
                'documents' => [$this->findModel($id)],
                'teachers_by_doc' => DocumentService::getTeacherByDocTeacher($id),
            ]);
        } else if ($ids !== null) {
            $documents = Document::getDocumentsByIds($ids);
            return $this->render('view', [
                'documents' => $documents,
                'teachers_by_doc' => DocumentService::getTeacherByDocTeacher($id),
            ]);
        }
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

        try {
            if (Yii::$app->request->isPost) {
                $model->uploadDocuments = UploadedFile::getInstances($model, 'uploadDocuments');
                $documents = $model->upload();

                $handlers = [
                    CosineSimilarity::class,
                    ContextSimilarity::class,
                    AvgSimilarity::class,
                ];

                if ((int)Settings::getSettings('SOFT_COSINE_ENABLE')) {
                    array_splice($handlers, 1, 0, SoftCosineSimilarity::class);
                }

                // file is uploaded successfully
                if ($documents !== null) {
                    if (count($documents) === 1) {
                        $handler = new DocumentHandler($documents[0], $handlers);
                        $handler->textHandle();
                        $model->saveDocument($documents[0]);

                        Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
                        $this->redirect(Url::toRoute(['update', 'id' => $documents[0]->id]));
                    } else {
                        $ids = '';
                        foreach ($documents as $document) {
                            $handler = new DocumentHandler($document, $handlers);
                            $handler->textHandle();
                            $model->saveDocument($document);
                            $ids .= ('id=' . $document->id . '&');
                        }
                        $ids = rtrim($ids, '&');

                        Yii::$app->session->setFlash('uploadDocument', 'Документы успешно загружены');
                        $this->redirect(Url::toRoute(['view', 'ids' => $ids]));
                    }
                }
            }
        } catch (ServerErrorHttpException $ex) {
            echo $ex->getMessage() . $ex->getTrace();
            die;
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
            $document->updateProperties($request->post('literature'), Property::getIdByProperty(Property::LITERATURE));
            $document->updateProperties($request->post('annotation'), Property::getIdByProperty(Property::ANNOTATIONS));
            $document->updateProperties($request->post('theme'), Property::getIdByProperty(Property::THEME));

            $document->updateTeachers($request->post('teachers'));
            $document->load($request->post());
            $document->document_type_id = $request->post('document_type_id');

            $type = $request->post('methodType');
            $document->section_id = $request->post(VsmSimilarity::getFieldName($type));
            if ($document->getDocumentSection() != null) {
                $document->getDocumentSection()->setSimilarType($type);
            }

            $document->save();

            $document->section->addSections($document->getVsm());

            return $this->redirect(['view', 'id' => $id]);
        } else {
            $document = Document::findOne($id);

            $teachers = ArrayHelper::map(Teacher::find()->all(), 'id', 'surname');
            $types = ArrayHelper::map(DocumentType::find()->all(), 'id', 'type');

            $properties = [
                'keywords' => Property::KEY_WORDS,
                'theme' => Property::THEME,
                'fios' => Property::FIO,
                'emails' => Property::EMAIL,
                'dates' => Property::DATES,
                'foundTeachers' => Property::TEACHER,
                'literature' => Property::LITERATURE,
                'annotation' => Property::ANNOTATIONS,
            ];

            $propertiesValue = [];
            foreach ($properties as $key => $property) {
                $propertiesValue += [$key => ArrayHelper::map(DocumentProperty::find()->where([
                    'document_id' => $id,
                    'property_id' => Property::getIdByProperty($property)
                ])->all(), 'id', 'value')];
            }

            $methodType = VsmSimilarity::getMethodTypes();
            if (!((int)Settings::getSettings('SOFT_COSINE_ENABLE'))) {
                unset($methodType[VsmSimilarity::SOFT_COSINE_TYPE]);
            }

            return $this->render('edit-after-load-document', array_merge([
                'teachers' => $teachers,
                'document' => $document,
                'types' => $types,
                'cosineSections' => $document->getSectionMap(VsmSimilarity::COSINE_TYPE),
                'softCosineSections' => $document->getSectionMap(VsmSimilarity::SOFT_COSINE_TYPE),
                'contextSections' => $document->getSectionMap(VsmSimilarity::CONTEXT_TYPE),
                'avgSections' => $document->getSectionMap(VsmSimilarity::AVG_TYPE),
                'methodType' => $methodType
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
        $this->delete($id);

        return $this->redirect(['index']);
    }

    private function delete($id)
    {
        $documentTeacher = DocumentTeacher::find()->where(['document_id' => $id])->all();
        $keywords = Keyword::find()->where(['document_id' => $id])->all();
        $properties = DocumentProperty::find()->where(['document_id' => $id])->all();
        $documentSections = Document::findOne($id)->getDocumentSections()->all();

        foreach ($documentTeacher as $item) {
            $item->delete();
        }
        foreach ($keywords as $keyword) {
            $keyword->delete();
        }
        foreach ($properties as $property) {
            $property->delete();
        }
        foreach ($documentSections as $documentSection) {
            $documentSection->delete();
        }

        $this->findModel($id)->delete();
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;
        $uploadForm = new UploadWebDocumentForm();

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {

            $userQuery = $uploadForm->theme;
            $userQuery = CommonHelper::getVsmFromQuery($userQuery);
            $section = new Section();
            $section->sections = json_encode($userQuery);
            $suitableDocuments = Document::getSuitableDocuments($section);

            $dataProvider = new ArrayDataProvider ([
                'allModels' => $suitableDocuments,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('show-documents-search', [
                'uploadForm' => $uploadForm,
                'docs' => $suitableDocuments,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('/document-upload/upload-web-form', ['model' => new UploadWebDocumentForm()]);
    }

    public function actionDeleteAll()
    {
        Yii::$app->session->setFlash("delete", "Удалено " . Document::find()->count() . " записей");
        foreach (Document::find()->all() as $document) {
            $this->delete($document->id);
        }

        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteByRegex()
    {
        $uploadForm = new UploadWebDocumentForm();
        $request = Yii::$app->request;

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {
            $documentsToDelete = Document::find()->where(['like', 'document_name', $uploadForm->theme . '%', false])->all();
            if ($documentsToDelete != null AND count($documentsToDelete) !== 0) {
                $i = 0;
                foreach ($documentsToDelete as $documentToDelete) {
                    $this->delete($documentToDelete->id);
                    $i++;
                }
            }
            Yii::$app->session->setFlash("delete", "Удалено " . $i . " записей");
            $searchModel = new DocumentSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('/document/input-field', ['model' => $uploadForm]);
    }
}
