<?php


namespace backend\modules\document\controllers;

use common\exceptions\ServiceUnavailable;
use backend\modules\document\models\upload\DocumentWebUpload;
use backend\modules\document\models\UploadWebDocumentForm;
use backend\modules\document\services\finder\GoogleScholarFinder;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Controller;


class DocumentUploadController extends Controller
{
    public function actionCreate($id)
    {
        try {
            $session = Yii::$app->session;
            if (!$session->hasProperty('documentToLoad_' . $id)) {
                throw new \Exception('Can\'t load document');
            }
            $documentToLoad = $session->get('documentToLoad_' . $id);
            $uploader = new DocumentWebUpload();
            $uploader->upload($documentToLoad);

            Yii::$app->session->setFlash('uploadDocument', 'Документ успешно загружен');
            $this->redirect(Url::toRoute(['/document/document/update', 'id' => $documentToLoad->id]));
        } catch (\Exception $ex) {

        }
    }


    public function actionUploadWeb()
    {
        $request = Yii::$app->request;
        $uploadForm = new UploadWebDocumentForm();

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {
            try {
                $finder = new GoogleScholarFinder();
                $docs = $finder->findDocuments($uploadForm);
            } catch (ServiceUnavailable $ex) {
                Yii::$app->session->setFlash('service', $ex->getMessage());
            }

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