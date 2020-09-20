<?php


namespace frontend\modules\document\models;


use Yii;
use yii\base\Model;

class UploadDocumentForm extends Model
{
    public $document;

    public function rules()
    {
        return [
            [['document'], 'file', 'skipOnEmpty' => false, 'extensions' => 'doc'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $document = new Document();
            $document->file_name_before = $this->document->baseName . '.' . $this->document->extension;
            $document->file_name_after = Yii::$app->getSecurity()->generatePasswordHash($document->file_name_before);
            $document->save();
            $this->document->saveAs('documents/' . $this->document->baseName . '.' . $this->document->extension);
            return true;
        } else {
            return false;
        }
    }
}