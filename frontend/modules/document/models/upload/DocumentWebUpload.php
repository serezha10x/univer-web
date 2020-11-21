<?php

namespace frontend\modules\document\models\upload;


use common\helpers\DocumentHelper;
use common\services\CurlService;
use frontend\modules\document\models\Document;
use frontend\modules\document\models\DocumentProperty;
use frontend\modules\document\models\DocumentTeacher;
use frontend\modules\document\models\DocumentType;
use frontend\modules\document\models\Property;
use frontend\modules\teacher\models\Teacher;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class DocumentWebUpload implements IDocumentUpload
{

    public function upload(Document $document)
    {
        $fileFormat = DocumentHelper::getFormat($document->doc_source);

        $document->document_type_id = DocumentType::getDocumentType('Внешний ресурс');
        $document->file_name_after = Document::getFileNameAfter($fileFormat);
        $document->save();

        $curl = new CurlService();
        $curl->downloadFile($document->doc_source, Yii::getAlias('@docs') . '/' . $document->file_name_after);
    }

    public function edit()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $teachers_id = $request->post('teachers');
            $document = $this->findModel($id);
            $document->load($request->post());
            $document->document_type_id = $request->post('document_type_id');
            $document->save();

            if ($teachers_id != null) {
                foreach ($teachers_id as $teacher_id) {
                    $documentTeacher = new DocumentTeacher();
                    $documentTeacher->teacher_id = $teacher_id;
                    $documentTeacher->document_id = $id;
                    $documentTeacher->save();
                }
            }

            return $this->redirect(['view', 'id' => $id]);
        } else {
            $teachers = [];
            $document = Document::findOne($id);
//            foreach(Teacher::find()->all() as $teacher) {
//                $teachers[] = [$teacher->id => $teacher->surname . $teacher->name];
//            }
//            var_dump($teachers);
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
//            var_dump($propertiesValue['foundTeachers']); exit();
//var_dump($propertiesValue['keywords']); exit();
            return $this->render('edit-after-load-document', array_merge([
                'teachers' => $teachers,
                'document' => $document,
                'types' => $types,
            ], $propertiesValue));
        }
    }

    public function save()
    {
        // TODO: Implement save() method.
    }
}