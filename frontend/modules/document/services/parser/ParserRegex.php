<?php


namespace frontend\modules\document\services\parser;


final class ParserRegex extends ParserBase
{
    protected $text;


    public function __construct(&$text)
    {
        parent::__construct($text);
    }


    public function parse()
    {
        $patterns = require __DIR__ . "/config/regex_patterns.php";
        $info = array();
//var_dump($patterns);
//echo $this->text;
        foreach ($patterns as $pattern_name => $pattern) {
            preg_match_all($pattern, $this->text, $matches);
            $info[$pattern_name] = array_unique($matches[0]);
        }
        return $info;
    }
}
