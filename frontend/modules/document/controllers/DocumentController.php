<?php

namespace frontend\modules\document\controllers;

use frontend\modules\document\models\Document;
use frontend\modules\document\models\DocumentSearch;
use frontend\modules\document\models\DocumentTeacher;
use frontend\modules\document\models\UploadDocumentForm;
use frontend\modules\document\services\DocumentService;
use frontend\modules\document\services\parser\ParserRegex;
use frontend\modules\teacher\models\Teacher;
use Yii;
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
            'teachers_by_doc' => DocumentService::getTeacherByDocTeacher($id)
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
        $model = new uploaddocumentform();

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

    public function actionUpload()
    {
        $model = new UploadDocumentForm();

        if (Yii::$app->request->isPost) {
            $model->upload_document = UploadedFile::getInstance($model, 'upload_document');
            $document = $model->upload();
            if ($document !== null) {
                // file is uploaded successfully
                $text = $document->read($document->file_name_after);
                $parsers['parse_regex'] = new ParserRegex($text);

                $parser_answer = [];
                foreach ($parsers as $key => $value) {
                    $parsed_data = $value->parse();
                    if (is_array($parsed_data)) {
                        foreach ($parsed_data as $key_data => $value_data) {
                            $parser_answer[$key_data] = $value_data;
                        }
                    } else {
                        $parser_answer[$key] = $value->parse();
                    }
                }

                Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
                $this->redirect(Url::toRoute(['document-edit', 'id' => $document->id]));
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionDocumentEdit($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $teachers_id = $request->post('teachers');
            $document = $this->findModel($id);
            $document->load($request->post());
            $document->save();

            foreach ($teachers_id as $teacher_id) {
                $documentTeacher = new DocumentTeacher();
                $documentTeacher->teacher_id = $teacher_id;
                $documentTeacher->document_id = $id;
                $documentTeacher->save();
            }

            return $this->redirect(['view', 'id' => $id]);
        } else {
            $document = Document::findOne($id);
            $teachers = ArrayHelper::map(Teacher::find()->all(), 'id', 'name');
            return $this->render('edit-after-load-document', [
                'teachers' => $teachers, 'document' => $document]);
        }
    }

    /**
     * Updates an existing Document model.
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
