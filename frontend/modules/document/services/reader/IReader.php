<?php


namespace frontend\modules\document\services\reader;


interface IReader
{
    public function read(string $filename, string $filepath): string;
}
