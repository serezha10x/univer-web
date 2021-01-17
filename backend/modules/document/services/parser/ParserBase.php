<?php


namespace backend\modules\document\services\parser;


abstract class ParserBase
{
    protected $text;

    public function __construct(&$text)
    {
        $this->text = $text;
    }

    abstract public function parse();
}
