<?php


namespace frontend\modules\document\services\parser;

use phpMorphy;
use phpMorphy_Exception;


final class ParserFrequency extends ParserBase
{
    protected $text;
    private $key_words;
    private $morphy;
    private $num_max = 5;


    public function __construct(&$text) {
        parent::__construct($text);
        $this->key_words = array();
    }


    public function parse()
    {
        try {
            $dir = $_SERVER['DOCUMENT_ROOT'] . "/vendor/cijic/phpmorphy/libs/phpmorphy/dicts";
            $lang = 'ru_RU';
            $this->morphy = new phpMorphy($dir, $lang);
        } catch(phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
        }

        $arr_words = $this->tokenize($this->text);

        $count = count($arr_words);
        // получаем части речи, необходимые для парсинга
        $need_words = require __DIR__ . "/config/need_part_speech.php";

        $array_defis = array();

        for ($i = 0; $i < $count; $i++) {
            if (mb_strlen($arr_words[$i]) <= 3) {
                unset($arr_words[$i]);
                continue;
            }
            $arr_words[$i] = mb_strtoupper($arr_words[$i]);

            if ($part = $this->morphy->getPartOfSpeech($arr_words[$i])) {
                // проверка на часть речи
                $isNeedPart = false;
                foreach ($need_words as $nw) {
                    if (in_array($nw, $part)) {
                        $isNeedPart = true;
                    }
                }
                if (!$isNeedPart) {
                    unset($arr_words[$i]);
                    continue;
                }

                $result = $this->morphy->findWord($arr_words[$i], phpMorphy::IGNORE_PREDICT);
                if ($result === false) {
                    // проверка на -
                    if (mb_strpos($arr_words[$i], "-")) {
                        $tmp = preg_split("@-@", $arr_words[$i]);
                        $temp_array = array();
                        foreach ($tmp as $s) {
                            // добавляем слова по отдельности
                            if (mb_strlen($s) >= 2) {
                                if (mb_substr_count($s, 'Е') > 0) {
                                    $true_change_word = $this->ChangeLetter($s, 'Е', 'Ё');
                                    if ($true_change_word != NULL) {
                                        $true_change_word = $this->morphy->lemmatize($true_change_word)[0];
                                        array_push($arr_words, $true_change_word);
                                        $temp_array[] = $true_change_word;
                                        $count++;
                                        continue;
                                    }
                                }
                                $count++;
                                array_push($arr_words, $s);
                                $temp_array[] = $s;
                            }
                        }

                        if (!$this->isUnique($array_defis, $temp_array)) {
                            $array_defis[] = $temp_array;
                        }
                        // проверка на идентичность слов
                        unset($arr_words[$i]);
                        continue;

                    // проверка на Ё
                      } else if (mb_substr_count($arr_words[$i], 'Е') > 0) {
                        $true_change_word = $this->ChangeLetter($arr_words[$i], 'Е', 'Ё');
                        if ($true_change_word != NULL) {
                            $arr_words[$i] = $true_change_word;
                            continue;
                        } else {
                            unset($arr_words[$i]);
                        }
                    } else {
                        unset($arr_words[$i]);
                    }
                }
            }
        }

        $arr_freq  = array_count_values($arr_words);
        $maxes = $this->getMaxes($arr_freq, $this->num_max);
        $temp_array = array();

        foreach ($arr_freq as $key => $value) {
            for ($i = 0; $i < $this->num_max; $i++) {
                if ($value == $maxes[$i]) {
                    $key_word = $this->morphy->lemmatize($key)[0];
                    $this->key_words[] = $key_word;
                }
            }
        }

        foreach ($array_defis as $arr_d) {
            $str_defis = "";
            foreach ($arr_d as $str) {
                $str_defis .= ($str . "-");
            }
            $str_defis = trim($str_defis, "-");
            if (!empty($str_defis)) {
                $this->key_words[] = $str_defis;
            }
        }

        $stop_words = require(__DIR__ . '/config/stop_words.php');
        foreach ($stop_words as $word) {
            $this->key_words = array_diff($this->key_words, array($word));
        }
        $this->key_words = $this->ArrayUnique($this->key_words);

        $dict_parse_text = "Частотный анализ текста: ";
        for ($i = 0; $i < count($this->key_words); $i++) {
            if ($i != count($this->key_words) - 1) $dict_parse_text .= $this->key_words[$i] . ", ";
            else $dict_parse_text .= $this->key_words[$i] . ".";
        }
        return $this->key_words;
    }


    private function tokenize(string &$text) : array {
        // убираем все, кроме букв
        $str_freq  = preg_replace('@([^А-Яа-яA-Za-z\s\-])@u', '', $text);
        // убираем все лишние пробелы
        $str_freq  = preg_replace('@\s{2,}@u', ' ', $str_freq);
        // разбиваем строку по пробелам
        return preg_split("@ @u", $str_freq);
    }


    private function isUnique($big_arr, $arr) : bool {
        if (count($big_arr) == 0) {
            return false;
        }
        foreach ($big_arr as $item) {
            if (count($item) != count($arr)) {
                continue;
            } else {
                $k = 0;
                $size = count($item);
                for ($i = 0; $i < $size; $i++) {
                    if ($item[$i] === $arr[$i]) {
                        $k++;
                    }
                }
                if ($k != $size) {
                    return true;
                } else {
                    continue;
                }
            }
        }
        return false;
    }


    private function getMaxes($arr_freq, $num_max) {
        $maxes = array();
        for ($i = 0; $i < $num_max; $i++) {
            $temp_max = -1;
            foreach ($arr_freq as $key => $value) {
                if ($i == 0) {
                    if ($value > $temp_max) {
                        $temp_max = $value;
                    }
                    continue;
                }
                else {
                    $isWas = false;
                    if ($value > $temp_max) {
                        for ($j = 0; $j < count($maxes); $j++) {
                            if ($value == $maxes[$j]) {
                                $isWas = true;
                            }
                        }
                        if (!$isWas) {
                            $temp_max = $value;
                        }
                    }
                }
            }
            if ($temp_max == 1) {
                $this->num_max--;
                continue;
            }
            $maxes[$i] = $temp_max;
        }
        return $maxes;
    }


    private function str_replace_once($search, $replace, $subject) : array {
        $count = mb_substr_count($subject, 'Е');
        if ($count === 0) {
            return false;
        }
        $pos = 0;
        $change_words = array();
        for($i = 0; $i < $count; $i++) {
            $pos = mb_strpos($subject, $search, $pos);
            $change_words[] = $this->mb_substr_replace($subject, $replace, $pos);
        }
        return $change_words;
    }

    function mb_substr_replace($original, $replacement, $position)
    {
        $startString = mb_substr($original, 0, $position, "UTF-8");
        $endString = mb_substr($original, $position + 1, mb_strlen($original), "UTF-8");

        $out = $startString . $replacement . $endString;

        return $out;
    }


    function ChangeLetter($word, $search, $replace) {
        if ($change_words = $this->str_replace_once($search, $replace, $word)) {
            foreach ($change_words as $change_word) {
                $result = $this->morphy->findWord($change_word, phpMorphy::IGNORE_PREDICT);
                if ($result !== false) {
                    return $change_word;
                }
            }
        }
        return NULL;
    }


    function ArrayUnique(array $arr) : array {
        $result = array();
        $elCounts = count($arr);

        for ($i = 0; $i < count($arr); ++$i) {
            foreach ($arr as $val) {
                if ($arr[$i] === $val && !in_array($val, $result) && $arr[$i] != '') {
                    $result[] = $arr[$i];
                }
            }
        }

        return $result;
    }
}
