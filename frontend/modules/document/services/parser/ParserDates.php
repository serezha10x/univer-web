<?php


namespace frontend\modules\document\services\parser;


final class ParserDates extends ParserBase
{
    const PATTERN = "@([“'\"\s]\d{1,2}[”'\"\s]\s?[а-я]+\s\d{4})|(\d{1,2}[\.]\d{1,2}[\.]\d{4})|(\d{4}\s?г\.?)@u";

    /**
     * ParserDates constructor.
     * @param $text
     */
    public function __construct($text)
    {
        parent::__construct($text);
    }

    public function parse()
    {
        preg_match_all(static::PATTERN, $this->text, $matches);
        return array_unique($matches[0]);
    }
}