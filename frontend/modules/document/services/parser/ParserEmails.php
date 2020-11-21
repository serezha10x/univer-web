<?php


namespace frontend\modules\document\services\parser;


final class ParserEmails extends ParserBase
{
    const PATTERN = "@\b[A-Za-z0-9._%+-]+\@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b@u";

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