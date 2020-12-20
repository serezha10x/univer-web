<?php


namespace frontend\modules\document\services;


interface DocumentConvertable
{
    public function convertToDocuments(array $from): array;
}