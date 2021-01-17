<?php


namespace backend\modules\document\models\upload;


use backend\modules\document\models\Document;

interface IDocumentUpload
{
    public function upload(Document $document = null);
}