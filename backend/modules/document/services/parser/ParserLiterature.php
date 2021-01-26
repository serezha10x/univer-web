<?php


namespace backend\modules\document\services\parser;


class ParserLiterature extends ParserBase
{
    protected $text;

    public function parse()
    {
        preg_match_all("@(Список литературы(.+|[\n\t\r])*)|(СПИСОК ИСПОЛЬЗУЕМОЙ ЛИТЕРАТУРЫ(.+|[\n\t\r])*)@ui",
            $this->text, $matches);
        $literatureList = $matches[0][0];
        preg_match_all("@(\d{1,3})?.+\n?@u", $literatureList, $matches);
        unset($matches[0][0]);

        return array_filter( $matches[0], function ($item) {
            if ($item === null OR mb_strlen($item) < 5) {
                return false;
            } return true;
        });
    }
}