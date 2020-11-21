<?php


namespace frontend\modules\document\models;


use Yii;
use yii\base\Exception;
use yii\base\Model;


class UploadDocumentForm extends Model
{
    const MAX_FILE_SIZE_MB = 10;
    public $upload_document;

    public function rules()
    {
        return [
            [['upload_document'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * self::MAX_FILE_SIZE_MB,
                'extensions' => Yii::$app->getModule('document')->params['allowFormats']],
        ];
    }

    /**
     * @return Document|null if save was unsuccess
     * @throws \yii\base\Exception
     */
    public function upload(): Document
    {
        if ($this->validate()) {
            $document = new Document();
            $document->document_name = $this->upload_document->baseName;
            $document->file_name_before = $this->upload_document->baseName . '.' . $this->upload_document->extension;
            $document->file_name_after = Document::getFileNameAfter($this->upload_document->extension);
            $document->save();
            if ($document->id === null) {
                throw new Exception('Document was not save!');
            }
            $this->upload_document->saveAs('@docs/' . $document->file_name_after);

            return $document;
        } else {
            return null;
        }
    }
}