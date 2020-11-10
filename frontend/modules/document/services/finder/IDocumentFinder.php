<?php


namespace frontend\modules\document\services\finder;


use frontend\modules\document\models\UploadWebDocumentForm;

interface IDocumentFinder
{
    public function findDocuments(UploadWebDocumentForm $form, int $limit = 10): array;

    public function convertToDocuments(array $parseData): array;

    public function filterDocuments(array $docs): array;
}