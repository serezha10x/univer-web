<?php


namespace backend\modules\document\services\reader;


use backend\modules\document\services\reader\base\Rtf;

class RtfReader implements IReader
{
    public function read(string $filename, string $filepath, $pages = self::DEFAULT_PAGES, $typeReading = self::BEGIN_END_PAGES): string
    {
        $rtf = new Rtf;
        return $rtf->rtf2text($filename);
    }
}
