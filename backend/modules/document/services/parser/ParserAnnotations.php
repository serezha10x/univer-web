<?php


namespace backend\modules\document\services\parser;


class ParserAnnotations extends ParserBase
{
    protected $text;

    public function parse()
    {
        preg_match_all("@Аннотация.*\n?.*@ui", $this->text, $matches);
        $annotation = preg_replace("@^Аннотация@ui", '', $matches[0][0]);
        $annotation = is_array($annotation) ? $annotation : [$annotation];

        return array_filter($annotation, function ($item) {
            if ($item === null OR mb_strlen($item) < 5) {
                return false;
            } return true;
        });
    }
}