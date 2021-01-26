<?php


namespace backend\modules\document\services\reader;


use backend\modules\document\services\reader\base\Doc;
use Exception;
use LukeMadhanga\DocumentParser;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Reader\Word2007;

class DocReader implements IReader
{

    public function read(string $filename, string $filepath, $pages = self::DEFAULT_PAGES, $typeReading = self::BEGIN_END_PAGES): string
    {
        $doc = new Doc();
        $doc->read("$filepath/$filename");
        $text = $doc->parse();
        $text = strip_tags($text);
        return mb_convert_encoding($text, "UTF-8", "auto");
    }
}
