<?php


namespace frontend\modules\document\services\reader;


interface IReader
{
    /* How to read document */
    const BEGIN_PAGES = 0;
    const END_PAGES = 1;
    const BEGIN_END_PAGES = 2;

    public function read(string $filename, string $filepath, $pages = 5, $typeReading = self::BEGIN_END_PAGES): string;
}
