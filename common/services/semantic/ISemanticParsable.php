<?php


namespace common\services\semantic;

interface ISemanticParsable
{
    public function getTermsByWords(string $word) : string;
}