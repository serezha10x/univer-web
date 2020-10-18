<?php


namespace frontend\modules\document\services\parser;


class ParserFio extends ParserBase
{
    const PATTERN = '@([А-Я][а-яё]+\s[А-Я][\.\s]{1,2}[А-Я][,.\s]?)|([А-Я][\.\s]{1,2}[А-Я][\.\s]{1,2}[А-Я][а-яё]+)@u';

    /**
     * ParserFio constructor.
     * @param $text
     */
    public function __construct($text)
    {
        parent::__construct($text);
    }

    public function parse()
    {
        preg_match_all(self::PATTERN, $this->text, $matches);
        return array_unique($matches[0]);
    }
}