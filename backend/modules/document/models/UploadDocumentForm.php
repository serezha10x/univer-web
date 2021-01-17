<?php


namespace backend\modules\document\models;


use backend\modules\document\models\upload\IDocumentUpload;
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
                'extensions' => Yii::$app->getModule('document')->params['allowFormats'], 'maxFiles' => self::MAX_FILES, 'minFiles' => 1],
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

        $file->saveAs('@docs/tmp/' . $document->file_name_after);

        return $document;
    }

    public function saveDocument(Document $document)
    {
        foreach ($this->uploadDocuments as $uploadDocument) {
            if ($uploadDocument->baseName === $document->document_name) {
                if ($document->section_id !== null) {
                    $this->createDir($document->getSection()->one()->name);

                    return rename(Yii::getAlias('@docs') . '/tmp/' . $document->file_name_after,
                        Yii::getAlias('@docs') . '/' . $document->getSection()->one()->name . '/' . $document->file_name_after);
                } else {
                    $this->createDir('Общее');

                    return $uploadDocument->saveAs('@docs/Общее/' . $document->file_name_after);
                }
            }
        }
    }

    public function createDir(string $dir, string $path = null)
    {
        $path = $path ?? Yii::getAlias('@docs');
        if (!is_dir("$path/$dir")) {
            mkdir("$path/$dir", 0777, true);
        }
    }
}