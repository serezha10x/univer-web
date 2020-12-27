<?php


namespace frontend\modules\document\models;


use frontend\modules\document\models\upload\IDocumentUpload;
use Yii;
use yii\base\Exception;
use yii\base\Model;


class UploadDocumentForm extends Model implements IDocumentUpload
{
    const MAX_FILE_SIZE_MB = 10;

    public $document_type_id;
    public $upload_document;

    public function rules()
    {
        return [
            [['document_type_id'], 'safe'],
            [['document_type_id'], 'integer'],
            [['upload_document'], 'required'],
            [['upload_document'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * self::MAX_FILE_SIZE_MB,
                'extensions' => Yii::$app->getModule('document')->params['allowFormats']],
        ];
    }

    /**
     * @param Document|null $document
     * @return bool|Document
     * @throws Exception
     */
    public function upload(Document $document = null)
    {
        if ($this->validate()) {
            $request = Yii::$app->request->post();
            $document = new Document();
            $document->document_name = $this->upload_document->baseName;
            $document->file_name_before = $this->upload_document->baseName . '.' . $this->upload_document->extension;
            $document->file_name_after = Document::getFileNameAfter($this->upload_document->extension);
            if ($request['document_type_id'] !== null) {
                $document->document_type_id = $request['document_type_id'];
            }
            $document->doc_source = Source::LOCAL_FILE;

            $document->save();
            if ($document->id === null) {
                throw new Exception('Document was not save!');
            }
            $this->upload_document->saveAs('@docs/' . $document->file_name_after);

            return $document;
        } else {
            return false;
        }
    }
}