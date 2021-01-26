<?php

namespace backend\modules\document\services\vsm;

use backend\modules\document\services\parser\ParserFrequency;
use backend\modules\settings\models\Settings;

class Vsm
{
    private $vsm;

    private $limit = 5;

    public function __construct()
    {
        $settedLimit = (int)Settings::getSettings('WORDS_FREQ_ANALYSIS');
        if ($settedLimit !== null) {
            $this->limit = $settedLimit;
        }
    }

    /* Формируем контекстный вектор */
    public function formVectorSpaceModel(ParserFrequency $freqData)
    {
        if (!is_array($freqData->getKeyFreqWords())) {
            return json_encode([]);
        }
        $this->vsm = [];
        foreach ($freqData->getKeyFreqWords() as $word => $freq) {
            $this->vsm[$word] = $this->calcTermFrequency($freq, $freqData->getCount());
        }

        $this->sortByWeight();
        $this->limit();

        return json_encode($this->vsm, JSON_UNESCAPED_UNICODE);
    }

    /* Считаем TF */
    private function calcTermFrequency($freq, $count)
    {
        return $freq / $count;
    }

    /* Сортируем по возрастанию */
    private function sortByWeight()
    {
        arsort($this->vsm);
    }

    /* Ограничиваем массив */
    private function limit()
    {
        $count = count($this->vsm);
        if ($count <= $this->limit) {
            return $this->vsm;
        } else {
            $this->vsm = array_slice($this->vsm, 0, $this->limit);
        }
    }
}