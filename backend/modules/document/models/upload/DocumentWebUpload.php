<?php

namespace backend\modules\document\models\upload;


use common\helpers\DocumentHelper;
use common\services\CurlService;
use backend\modules\document\models\Document;
use backend\modules\document\models\DocumentType;
use Yii;

class DocumentWebUpload implements IDocumentUpload
{

    public function upload(Document $document = null)
    {
        $fileFormat = DocumentHelper::getFormat($document->doc_source);

        $document->document_type_id = DocumentType::getDocumentType('Внешний ресурс');
        $document->file_name_after = Document::getFileNameAfter($fileFormat);
        $document->save();

        $curl = new CurlService();
        $curl->downloadFile($document->doc_source, Yii::getAlias('@docs') . '/' . $document->file_name_after);
    }
}