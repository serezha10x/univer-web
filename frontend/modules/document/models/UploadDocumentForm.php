<?php


namespace frontend\modules\document\models;


use frontend\modules\document\models\upload\IDocumentUpload;
use Yii;
use yii\base\Exception;
use yii\base\Model;


class UploadDocumentForm extends Model implements IDocumentUpload
{
    const MAX_FILE_SIZE_MB = 10;
    const MAX_FILES = 10;

    public $document_type_id;
    public $uploadDocuments;

    public function rules()
    {
        return [
            [['document_type_id'], 'safe'],
            [['document_type_id'], 'integer'],
            [['uploadDocuments'], 'required'],
            [['uploadDocuments'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * self::MAX_FILE_SIZE_MB,
                'extensions' => Yii::$app->getModule('document')->params['allowFormats'], 'maxFiles' => self::MAX_FILES],
        ];
    }

    /**
     * @param Document|null $document
     * @return bool|array
     * @throws Exception
     */
    public function upload(Document $document = null)
    {
        if ($this->validate()) {

            $documents = [];
            foreach ($this->uploadDocuments as $upload_document) {
                $documents[] = $this->uploadDocument($upload_document);
            }

            return $documents;
        } else {
            return false;
        }
    }

    private function uploadDocument(yii\web\UploadedFile $file)
    {
        $request = Yii::$app->request->post();
        $document = new Document();
        $document->document_name = $file->baseName;
        $document->file_name_before = $file->baseName . '.' . $file->extension;
        $document->file_name_after = Document::getFileNameAfter($file->extension);
        if ($request['document_type_id'] !== null) {
            $document->document_type_id = $request['document_type_id'];
        }
        $document->doc_source = Source::LOCAL_FILE;

        $document->save();
        if ($document->id === null) {
            throw new Exception('Document was ' . $file->baseName . ' not save!');
        }
        $file->saveAs('@docs/' . $document->file_name_after);

        return $document;
    }
}