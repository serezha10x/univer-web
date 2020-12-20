<?php


namespace frontend\modules\document\services\reader;


use frontend\modules\document\services\reader\base\Rtf;

class RtfReader implements IReader
{
    public function read(string $filename, string $filepath, $pages = 5, $typeReading = self::BEGIN_END_PAGES): string
    {
        $rtf = new Rtf;
        return $rtf->rtf2text($filename);
    }
}
