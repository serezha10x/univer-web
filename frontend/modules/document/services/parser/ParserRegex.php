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
        $parse_text = '';
        $info = array();
        $info_str = array();

        foreach ($patterns as $pattern_name => $pattern) {
            $parse_text .= "<br>" . $pattern_name . ": ";
            preg_match_all($pattern, $this->text, $matches);
            $info[$pattern_name] = $matches[0];//array_unique($matches[0]);
            if (count($info[$pattern_name]) != 0) {
                $temp_str = $pattern_name . ': ';
                $size = count($info[$pattern_name]);
                for ($i = 0; $i < $size; $i++) {
                    if ($info[$pattern_name][$i] != '' and strlen($info[$pattern_name][$i]) > 1) {
                        if ($i == $size - 1) {
                            $temp_str .= $info[$pattern_name][$i] . ".";
                        } else {
                            $temp_str .= $info[$pattern_name][$i] . ", ";
                        }
                    }
                }
                $info_str[$pattern_name] = $temp_str;
            }
        }
        return $info_str;
    }
}
