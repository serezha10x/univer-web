<?php


namespace frontend\modules\document\controllers;

use frontend\modules\document\models\UploadWebDocumentForm;
use frontend\modules\document\services\finder\GoogleScholarFinder;
use Yii;
use yii\web\Controller;


class DocumentUploadController extends Controller
{
    public function actionUploadWeb()
    {
        $request = Yii::$app->request;
        $uploadForm = new UploadWebDocumentForm();

        if ($request->isPost && $uploadForm->load(Yii::$app->request->post())) {
            $finder = new GoogleScholarFinder();
            $docs = $finder->findDocuments($uploadForm);

            return $this->render('show-upload-web-document', [
                'uploadForm' => $uploadForm,
                'docs' => $docs
            ]);
        }

        return $this->render('upload-web-form', ['model' => new UploadWebDocumentForm()]);
    }
}