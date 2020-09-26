<?php


namespace frontend\modules\document\models;


use frontend\modules\document\services\reader\ReaderCreator;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;


class UploadDocumentForm extends Model
{
    public $upload_document;
    const MAX_FILE_SIZE_MB = 10;
    const NUM_CHARS_FILE_NAME = 12;

    public function rules()
    {
        return [
            [['upload_document'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * self::MAX_FILE_SIZE_MB,
                'extensions' => Yii::$app->getModule('document')->params['allowFormats']],
        ];
    }

    /**
     * @return Document|false if save was unsuccess
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

    public function read(string $filename): string
    {
        $reader = ReaderCreator::factory($this->upload_document->extension);
        return $reader->read($filename);
    }

    public function parse(string $text)
    {

    }
}