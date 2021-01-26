<?php


namespace backend\modules\document\services\reader;


interface IReader
{
    /* How to read document */
    const ALL_PAGES = 0;
    const BEGIN_PAGES = 1;
    const END_PAGES = 2;
    const BEGIN_END_PAGES = 3;
    const MIDDLE_PAGES = 4;

    const readTypeNames = [
        'ВЕСЬ ТЕКСТ',
        'НАЧАЛО ТЕКСТА',
        'КОНЕЦ ТЕКСТА',
        'РАЗРЕЖЕННЫЙ ТЕКСТ',
        'СЕРЕДИНА ТЕКСТА'
    ];

    const ONE_PAGE_EQ_WORDS = 250;
    const DEFAULT_PAGES = 3;

    public function read(string $filename, string $filepath, $pages = self::DEFAULT_PAGES, $typeReading = self::BEGIN_END_PAGES): string;
}
