<?php


namespace App\Parser;


final class ParserWordsCase extends ParserBase
{
    protected $text;


    public function __construct(&$text) {
        parent::__construct($text);
    }


    public function parse()
    {
        $tokens = tokenize($this->text, \TextAnalysis\Tokenizers\GeneralTokenizer::class);

        $theme = '';
        $isPrevUpper = false;
        $isCurUpper = false;

        foreach ($tokens as $token) {
            if ($this->my_ctype_upper($token)) {
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
            if ($isPrevUpper && $isCurUpper) {
                $theme .= ($token . ' ');
            }
            $isPrevUpper = $isCurUpper;
        }

        return 'Тема: ' . $theme;
    }


    private function my_ctype_upper(string $str) : bool {
        if (preg_match('@[а-яё0-9.,;-·:/?!№a-z]@u', $str)) return false;
        else return true;
    }
}
