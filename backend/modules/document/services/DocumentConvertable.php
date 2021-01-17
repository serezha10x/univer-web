<?php


namespace backend\modules\document\services;


interface DocumentConvertable
{
    public function convertToDocuments(array $from): array;
}