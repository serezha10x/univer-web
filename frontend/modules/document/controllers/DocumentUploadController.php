<?php


namespace frontend\modules\document\controllers;

use frontend\modules\document\models\upload\DocumentWebUpload;
use frontend\modules\document\models\UploadWebDocumentForm;
use frontend\modules\document\services\finder\GoogleScholarFinder;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Controller;


class DocumentUploadController extends Controller
{
    public function actionCreate($id)
    {
        $documentToLoad = Yii::$app->session->get('documentToLoad_' . $id);
        $uploader = new DocumentWebUpload();
        try {
            $uploader->upload($documentToLoad);
            Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
            $this->redirect(Url::toRoute(['document-edit', 'id' => $documentToLoad->id]));
        } catch (\Exception $ex) {

        }
    }


    public function actionUploadWeb()
    {
        $request = Yii::$app->request;
        $uploadForm = new UploadWebDocumentForm();

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {
            $finder = new GoogleScholarFinder();
            $docs = $finder->findDocuments($uploadForm);

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

        return $this->render('upload-web-form', ['model' => new UploadWebDocumentForm()]);
    }
}