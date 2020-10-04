<?php


namespace frontend\modules\document\models;


use Yii;
use yii\base\Exception;
use yii\base\Model;


class UploadDocumentForm extends Model
{
    const MAX_FILE_SIZE_MB = 10;
    const NUM_CHARS_FILE_NAME = 50;
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
            $document->file_name_before = $this->upload_document->baseName . '.' . $this->upload_document->extension;
            $document->file_name_after = (Yii::$app
                    ->getSecurity()
                    ->generateRandomString(self::NUM_CHARS_FILE_NAME)) . '.' . $this->upload_document->extension;
            //$document->file_name_after = $document->file_name_before;
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