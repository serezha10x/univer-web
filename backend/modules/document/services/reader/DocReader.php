<?php


namespace backend\modules\document\services\reader;


use backend\modules\document\services\reader\base\Doc;

class DocReader implements IReader
{

    public function read(string $filename, string $filepath, $pages = 5, $typeReading = self::BEGIN_END_PAGES): string
    {
        $doc = new Doc();
        $doc->read("$filepath/$filename");
        $text = $doc->parse();
        $text = strip_tags($text);
        return mb_convert_encoding($text, "UTF-8", "auto");
    }
}
