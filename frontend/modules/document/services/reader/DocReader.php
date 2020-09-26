<?php


namespace frontend\modules\document\services\reader;


class DocReader implements IReader
{

    public function read(string $filename): string
    {
        $doc = new Doc();
        $doc->read($filename);
        $text = $doc->parse();
        $text = strip_tags($text);
        return mb_convert_encoding($text, "UTF-8", "auto");
    }
}
