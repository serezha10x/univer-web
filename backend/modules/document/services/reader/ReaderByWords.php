<?php


namespace backend\modules\document\services\reader;


trait ReaderByWords
{
    public function readByWords($text, int $from, int $to)
    {
        $textByPages = '';
        $chars = mb_strlen($text);

        $start = $finish = 0;
        $i = 0;

        $wordsNeed = $to - $from;
        $j = 0;
        for(; $i < $chars OR $j < $wordsNeed; $i++) {
            $ch = mb_substr($text, $i, 1);
            if ($j === $from) {
                $start = $i;
            }
            if ($j === $to) {
                $finish = $i;
            }
            if ($ch === ' ') {
                ++$j;
            }
            if ($i >= $chars - 1) {
                $finish = $i;
            }
        }

        return substr($text, $start, $finish - $start);
    }

    public function getPages($text)
    {
        return substr_count($text, ' ');
    }
}