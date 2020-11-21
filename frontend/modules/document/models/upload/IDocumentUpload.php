<?php


namespace frontend\modules\document\models\upload;


use frontend\modules\document\models\Document;

interface IDocumentUpload
{
    public function upload(Document $document);

    public function edit();

    public function save();
}