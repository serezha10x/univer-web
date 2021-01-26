<?php


namespace backend\modules\document\services\parser;


final class ParserTheme extends ParserBase
{
    protected $text;
    protected $stopWords = ['УДК'];

    public function __construct(&$text)
    {
        parent::__construct($text);
    }


    public function parse()
    {
        $tokens = tokenize($this->text, \TextAnalysis\Tokenizers\GeneralTokenizer::class);

        $theme = '';
        $isPrevUpper = false;

        foreach ($tokens as $token) {
            if ($this->my_ctype_upper($token) AND !$this->isStopWord($token)) {
                $isCurUpper = true;
                $theme .= ($token . ' ');
            } else {
                if ($isPrevUpper && strlen($theme) !== 0) {
                    break;
                } else {
                    $isPrevUpper = $isCurUpper = false;
                    $theme = '';
                    continue;
                }
            }
            $isPrevUpper = $isCurUpper;
        }

        return [trim($theme)];
    }


    private function my_ctype_upper(string $str): bool
    {
        if (preg_match('@[а-яёa-z]@u', $str)) {
            return false;
        } else {
            return true;
        }
    }

    private function isStopWord($word)
    {
        return in_array($word, $this->stopWords);
    }
}
