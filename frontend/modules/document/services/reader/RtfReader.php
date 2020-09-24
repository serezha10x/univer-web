<?php


namespace App\Reader;


class RtfReader implements IReader
{
    public function read(string $filename): string
    {
        $rtf = new Rtf;
        return $rtf->rtf2text($filename);
    }
}
